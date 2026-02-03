<?php

namespace Nasirkhan\ModuleManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Nasirkhan\ModuleManager\Services\ModuleVersion;

class ModuleDiffCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:diff {module : The module to compare}
                            {--detailed : Show detailed file-by-file comparison}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show differences between package and published module';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $moduleName = $this->argument('module');
        $packagePath = base_path("vendor/nasirkhan/module-manager/src/Modules/{$moduleName}");
        $publishedPath = base_path("Modules/{$moduleName}");

        // Check if module exists in package
        if (! File::exists($packagePath)) {
            $this->components->error("Module '{$moduleName}' not found in package.");

            return self::FAILURE;
        }

        // Get version info
        $versionService = app(ModuleVersion::class);
        $moduleData = $versionService->getModuleData($moduleName);
        $version = $moduleData['version'] ?? 'unknown';

        // Check if module is published
        if (! File::exists($publishedPath)) {
            $this->components->warn("Module '{$moduleName}' v{$version} has not been published yet.");
            $this->line('It\'s using the package version (updateable via composer).');
            $this->newLine();
            $this->line('To publish: <fg=green>php artisan module:publish '.$moduleName.'</>');

            return self::SUCCESS;
        }

        // Compare versions
        $this->newLine();
        $this->components->twoColumnDetail("<fg=bright-blue>Comparing Module:</> {$moduleName}", "v{$version}");
        
        if (!empty($moduleData['description'])) {
            $this->components->twoColumnDetail("<fg=gray>Description:</>", $moduleData['description']);
        }
        
        if (!empty($moduleData['requires'])) {
            $deps = implode(', ', $moduleData['requires']);
            $this->components->twoColumnDetail("<fg=gray>Dependencies:</>", $deps);
        }
        
        $this->newLine();

        // Get file lists
        $packageFiles = $this->getFileList($packagePath);
        $publishedFiles = $this->getFileList($publishedPath);

        $onlyInPackage = array_diff($packageFiles, $publishedFiles);
        $onlyInPublished = array_diff($publishedFiles, $packageFiles);
        $common = array_intersect($packageFiles, $publishedFiles);

        $hasChanges = false;

        // Statistics
        $stats = [
            'total_package' => count($packageFiles),
            'total_published' => count($publishedFiles),
            'new_in_package' => count($onlyInPackage),
            'removed_or_custom' => count($onlyInPublished),
            'common' => count($common),
        ];

        // Files only in package (new in package)
        if (! empty($onlyInPackage)) {
            $hasChanges = true;
            $this->components->warn("New files in package [{$stats['new_in_package']}]:");
            
            // Group by directory
            $grouped = $this->groupFilesByDirectory($onlyInPackage);
            
            foreach ($grouped as $dir => $files) {
                $this->line("  <fg=blue>{$dir}/</>");
                foreach ($files as $file) {
                    $this->line("    <fg=green>+</> ".basename($file));
                }
            }
            $this->newLine();
        }

        // Files only in published (removed from package or custom)
        if (! empty($onlyInPublished)) {
            $hasChanges = true;
            $this->components->info("Files only in your version [{$stats['removed_or_custom']}]:");
            
            // Group by directory
            $grouped = $this->groupFilesByDirectory($onlyInPublished);
            
            foreach ($grouped as $dir => $files) {
                $this->line("  <fg=blue>{$dir}/</>");
                foreach ($files as $file) {
                    $this->line("    <fg=red>-</> ".basename($file));
                }
            }
            $this->newLine();
        }

        // Check for modified files
        $modifiedFiles = $this->getModifiedFiles($packagePath, $publishedPath, $common);

        if (! empty($modifiedFiles)) {
            $hasChanges = true;
            $this->components->warn("Modified files [".count($modifiedFiles)."]:");
            
            // Group by directory
            $grouped = $this->groupFilesByDirectory($modifiedFiles);
            
            foreach ($grouped as $dir => $files) {
                $this->line("  <fg=blue>{$dir}/</>");
                foreach ($files as $file) {
                    $this->line("    <fg=yellow>M</> ".basename($file));

                    if ($this->option('detailed')) {
                        $this->showFileDiff($packagePath.'/'.$file, $publishedPath.'/'.$file);
                    }
                }
            }
            $this->newLine();
        }

        // Display statistics
        $this->components->twoColumnDetail('<fg=bright-blue>Statistics:</>', '');
        $this->components->twoColumnDetail('  Total files in package', $stats['total_package']);
        $this->components->twoColumnDetail('  Total files published', $stats['total_published']);
        $this->components->twoColumnDetail('  Unchanged files', $stats['common'] - count($modifiedFiles));
        $this->newLine();

        // Summary
        if (! $hasChanges) {
            $this->components->info('✓ No differences found. Your module matches the package version.');
        } else {
            $this->components->warn('⚠ Recommendation:');
            $this->line('  Review the changes above and decide whether to:');
            $this->line('  - Manually merge new features from package');
            $this->line('  - Keep your customizations as-is');
            $this->line('  - Re-publish and re-apply customizations');
            $this->newLine();
            $this->line('  Use <fg=green>--detailed</> flag to see line-by-line differences');
        }

        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Get list of files in directory.
     */
    protected function getFileList(string $path): array
    {
        if (! File::exists($path)) {
            return [];
        }

        return collect(File::allFiles($path))
            ->map(fn ($file) => str_replace($path.DIRECTORY_SEPARATOR, '', $file->getPathname()))
            ->map(fn ($file) => str_replace('\\', '/', $file)) // Normalize path separators
            ->toArray();
    }

    /**
     * Get list of modified files.
     */
    protected function getModifiedFiles(string $packagePath, string $publishedPath, array $commonFiles): array
    {
        $modified = [];

        foreach ($commonFiles as $file) {
            $packageFile = $packagePath.'/'.$file;
            $publishedFile = $publishedPath.'/'.$file;

            if (File::exists($packageFile) && File::exists($publishedFile)) {
                $packageHash = md5_file($packageFile);
                $publishedHash = md5_file($publishedFile);

                if ($packageHash !== $publishedHash) {
                    $modified[] = $file;
                }
            }
        }

        return $modified;
    }

    /**
     * Group files by directory.
     */
    protected function groupFilesByDirectory(array $files): array
    {
        $grouped = [];

        foreach ($files as $file) {
            $dir = dirname($file);
            if ($dir === '.') {
                $dir = 'root';
            }
            
            if (!isset($grouped[$dir])) {
                $grouped[$dir] = [];
            }
            
            $grouped[$dir][] = $file;
        }

        ksort($grouped);

        return $grouped;
    }

    /**
     * Show simple diff between two files.
     */
    protected function showFileDiff(string $file1, string $file2): void
    {
        $lines1 = explode("\n", File::get($file1));
        $lines2 = explode("\n", File::get($file2));

        $maxLines = max(count($lines1), count($lines2));

        $this->line('    <fg=gray>┌─ Diff Preview (first 5 lines) ─────────────────</>>');

        $shownLines = 0;
        for ($i = 0; $i < min($maxLines, 5); $i++) {
            $line1 = $lines1[$i] ?? '';
            $line2 = $lines2[$i] ?? '';

            if ($line1 !== $line2) {
                if (! empty($line1)) {
                    $this->line('    <fg=red>  - '.substr($line1, 0, 50).'</>');
                }
                if (! empty($line2)) {
                    $this->line('    <fg=green>  + '.substr($line2, 0, 50).'</>');
                }
                $shownLines++;
            }
        }

        if ($shownLines === 0) {
            $this->line('    <fg=gray>  (Differences in whitespace or later in file)</>');
        }

        $this->line('    <fg=gray>└────────────────────────────────────────────────</>>');
        $this->newLine();
    }
}
