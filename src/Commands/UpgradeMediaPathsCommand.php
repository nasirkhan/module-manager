<?php

namespace Nasirkhan\ModuleManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\Finder;

/**
 * Guided upgrade for the host-relative media-path change (module-manager v9 /
 * laravel-admin v3).
 *
 * The refactor stores image/avatar columns as host-relative paths instead of
 * absolute URLs. This command walks a project on an older version through the
 * three things that change:
 *
 *   1. Host trait requirement — the models now call imagePathFromUrl() and
 *      imageUrlFromPath(), provided by App\Models\Traits\HasHashedMediaTrait.
 *   2. Existing data — backfill migrations rewrite absolute URLs already in the
 *      database to relative paths.
 *   3. Call sites — code that reads ->image / ->avatar expecting an absolute
 *      URL must switch to ->image_full_url / ->avatar_full_url.
 *
 * Steps 1 and 2 are automated (with confirmation); step 3 is scanned and
 * reported for manual review — rewriting application code is never done blindly.
 */
class UpgradeMediaPathsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module-manager:upgrade-media-paths
        {--dry-run : Only report; make no changes and run no migrations}
        {--scan-only : Skip the trait and migration checks; only scan call sites}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrade a project to host-relative image/avatar paths (trait check, backfill, call-site scan)';

    /**
     * The backfill migrations that rewrite absolute URLs to relative paths.
     */
    protected array $backfillMigrations = [
        '2026_11_10_000000_backfill_categories_image_relative_path',
        '2026_11_10_000000_backfill_posts_image_relative_path',
        '2026_11_10_000000_backfill_tags_image_relative_path',
        '2026_11_10_000000_backfill_users_avatar_relative_path',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->newLine();
        $this->components->info('Host-relative media-path upgrade');

        if ($this->option('dry-run')) {
            $this->components->warn('Running in --dry-run mode: nothing will be changed.');
        }

        $this->newLine();

        if (! $this->option('scan-only')) {
            if ($this->checkTrait() === self::FAILURE) {
                return self::FAILURE;
            }

            $this->checkBackfill();
        }

        $this->scanCallSites();

        $this->newLine();
        $this->components->info('Upgrade check complete.');
        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Step 1 — verify the host trait exposes the helper methods the models need.
     */
    protected function checkTrait(): int
    {
        $this->components->twoColumnDetail('<fg=bright-blue>1. Host trait</>', '');

        if (! class_exists(\App\Models\User::class)) {
            $this->components->error('App\Models\User not found — is this a Laravel Starter project?');

            return self::FAILURE;
        }

        $missing = array_filter(
            ['imagePathFromUrl', 'imageUrlFromPath'],
            fn (string $method) => ! method_exists(\App\Models\User::class, $method)
        );

        if (empty($missing)) {
            $this->components->task('HasHashedMediaTrait has imagePathFromUrl() and imageUrlFromPath()', fn () => true);

            return self::SUCCESS;
        }

        $this->components->error('Missing method(s) on the media trait: '.implode(', ', $missing));
        $this->line('  Add them to <fg=yellow>app/Models/Traits/HasHashedMediaTrait.php</> before upgrading.');
        $this->line('  See: '.'https://github.com/nasirkhan/module-manager (UPGRADE.md).');
        $this->newLine();

        // This is a hard requirement; without it the models fatal at runtime.
        return self::FAILURE;
    }

    /**
     * Step 2 — report (and optionally run) the data backfill migrations.
     */
    protected function checkBackfill(): void
    {
        $this->components->twoColumnDetail('<fg=bright-blue>2. Data backfill</>', '');

        try {
            $ran = DB::table('migrations')->pluck('migration')->all();
        } catch (\Throwable $e) {
            $this->components->warn('Could not read the migrations table — run "php artisan migrate" first.');

            return;
        }

        $pending = array_values(array_diff($this->backfillMigrations, $ran));

        if (empty($pending)) {
            $this->components->task('All backfill migrations already applied', fn () => true);

            return;
        }

        foreach ($pending as $migration) {
            $this->line("  <fg=gray>→ pending:</> {$migration}");
        }

        if ($this->option('dry-run')) {
            $this->components->warn(count($pending).' backfill migration(s) pending (not run in --dry-run).');

            return;
        }

        if ($this->confirm('Run "php artisan migrate" now to apply the backfill?', true)) {
            $this->call('migrate', ['--force' => true]);
        } else {
            $this->components->warn('Skipped. Run "php artisan migrate" before deploying.');
        }
    }

    /**
     * Step 3 — scan for ->image / ->avatar reads that may need an absolute URL.
     */
    protected function scanCallSites(): void
    {
        $this->components->twoColumnDetail('<fg=bright-blue>3. Call-site scan</>', '');

        $roots = array_filter([
            base_path('app'),
            base_path('Modules'),
            base_path('resources/views'),
            base_path('routes'),
        ], fn (string $path) => File::isDirectory($path));

        // Absolute-URL contexts: a relative path here is a real bug.
        $absoluteContext = '/(og:image|meta_og|property=["\']og|twitter:image|rss|<feed|sitemap|schema\.org|->sendMail|Mail::|->route\(|url\(\s*\$)/i';

        $flagged = [];

        foreach ($roots as $root) {
            $finder = (new Finder)
                ->files()
                ->in($root)
                ->name(['*.php', '*.blade.php'])
                ->notPath('vendor')
                ->notPath('node_modules');

            foreach ($finder as $file) {
                $relative = str_replace(base_path().DIRECTORY_SEPARATOR, '', $file->getRealPath());
                $lineNo = 0;

                foreach (preg_split('/\R/', $file->getContents()) as $line) {
                    $lineNo++;

                    if (! preg_match('/->(image|avatar)\b/', $line)) {
                        continue;
                    }

                    // Already safe: wrapped in asset() or using the full-URL accessor,
                    // or a write/media call rather than a read.
                    if (preg_match('/(image_full_url|avatar_full_url|asset\(|imageUrlFromPath|imagePathFromUrl|->image\s*=|->avatar\s*=|addMedia|clearMediaCollection)/', $line)) {
                        continue;
                    }

                    $needsAbsolute = preg_match($absoluteContext, $line) === 1;

                    $flagged[] = [
                        'file' => $relative,
                        'line' => $lineNo,
                        'priority' => $needsAbsolute ? 'HIGH' : 'review',
                        'code' => trim($line),
                    ];
                }
            }
        }

        if (empty($flagged)) {
            $this->components->task('No raw ->image / ->avatar reads needing attention', fn () => true);

            return;
        }

        // High-priority first.
        usort($flagged, fn ($a, $b) => ($a['priority'] === 'HIGH' ? 0 : 1) <=> ($b['priority'] === 'HIGH' ? 0 : 1));

        $high = array_filter($flagged, fn ($r) => $r['priority'] === 'HIGH');

        $this->newLine();
        $this->components->warn(count($flagged).' raw read(s) found — '.count($high).' in absolute-URL context (HIGH).');
        $this->line('  Replace with <fg=green>->image_full_url</> / <fg=green>->avatar_full_url</> where an absolute URL is required.');
        $this->newLine();

        foreach ($flagged as $row) {
            $tag = $row['priority'] === 'HIGH' ? '<fg=red>HIGH </>' : '<fg=gray>review</>';
            $this->line("  {$tag} {$row['file']}:{$row['line']}");
            $this->line("        <fg=gray>{$row['code']}</>");
        }

        $this->newLine();
    }
}
