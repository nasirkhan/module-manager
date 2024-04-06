<?php

namespace Nasirkhan\ModuleManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModuleBuildCommand extends Command
{
    /**
     * The laravel filesystem instance.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * The laravel component Factory instance.
     *
     * @var \Illuminate\Console\View\Components\Factory
     */
    protected $component;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'module:build {moduleName : The name of the module to be built} {--force : Repace the existing module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $force = $this->option('force');

        $config = config('module-manager');

        // Module name | Single word | Ucfirst
        $moduleName = Str::ucfirst(Str::singular(Str::studly($this->argument('moduleName'))));

        $this->generate($moduleName, $force);
    }

    public function generate($moduleName, $force)
    {
        $this->components->info('Generating module: '.$moduleName);
        // $this->info('Generating module: '.$moduleName."\n");

        $config = config('module-manager');

        $namespace = $config['namespace'];
        $moduleNamePlural = Str::plural($moduleName);
        $moduleNameLower = Str::lower($moduleName);
        $moduleNameLowerPlural = Str::plural($moduleNameLower);

        $composerVendor = $config['composer']['vendor'];
        $composerAuthor = $config['composer']['author']['name'];
        $composerAuthorEmail = $config['composer']['author']['email'];

        $search = ['{{moduleName}}', '{{moduleNamePlural}}', '{{moduleNameLower}}', '{{moduleNameLowerPlural}}', '{{namespace}}', '{{composerVendor}}', '{{composerAuthor}}', '{{composerAuthorEmail}}'];
        $replace = [$moduleName, $moduleNamePlural, $moduleNameLower, $moduleNameLowerPlural, $namespace, $composerVendor, $composerAuthor, $composerAuthorEmail];

        $basePath = $namespace.'/'.$moduleName;

        if (File::isDirectory($basePath)) {
            if ($force) {
                $this->components->warn("{$moduleName} - Module already exists. Replacing...");
                File::deleteDirectory($basePath);
                File::makeDirectory($basePath);
                $this->info("  '$basePath' - new directory created");
                $this->createFiles($moduleName, $basePath, $search, $replace, $force);
            } else {
                $this->components->error("{$moduleName} - Module already exists! Use --force to replace.");

                return;
            }
        } else {
            $this->components->task("New Directory: $basePath", function () use ($basePath, $force) {
                File::makeDirectory($basePath, 0755, true, $force);
            });

            $this->createFiles($moduleName, $basePath, $search, $replace, $force);
        }

        $this->enableModule($moduleName);
    }

    public function createFiles($moduleName, $basePath, $search, $replace, $force)
    {
        $moduleNamePlural = Str::plural($moduleName);
        $moduleNameLower = Str::lower($moduleName);
        $moduleNameLowerPlural = Str::plural($moduleNameLower);

        $config = config('module-manager');

        $stubs_path = $config['stubs']['path'];

        $files_list = $config['module']['files'];

        foreach ($files_list as $file => $file_path) {
            $content_stub = File::get("$stubs_path/".$file_path[0]);
            $content = str_replace($search, $replace, $content_stub);

            $destination_value = $this->setFilePath($file, $file_path[1], $moduleName);

            $destination = "$basePath/".$this->setFilePath($file, $file_path[1], $moduleName);

            $pathToFile = $destination_value;

            if (count(explode('/', $pathToFile)) > 1) {
                $fileName = basename($pathToFile);

                $folders = explode('/', str_replace('/'.$fileName, '', $pathToFile));

                $currentFolder = "$basePath/";
                foreach ($folders as $folder) {
                    $currentFolder .= $folder.DIRECTORY_SEPARATOR;

                    if (!File::isDirectory($currentFolder)) {
                        File::makeDirectory($currentFolder);
                    }
                }
            }

            if (File::exists($destination)) {
                if ($force) {
                    $this->components->task("Replaced File: $destination", function () use ($destination, $content) {
                        File::put($destination, $content);
                    });
                } else {
                    $this->components->error("{$destination} - file already exists!");
                }
            } else {
                $this->components->task("New File: $destination", function () use ($destination, $content) {
                    File::put($destination, $content);
                });
            }
        }
    }

    public function setFilePath($filetype, $filePath, $moduleName)
    {
        $value = '';
        $moduleNamePlural = Str::plural($moduleName);
        $moduleNameLower = Str::lower($moduleName);
        $moduleNameLowerPlural = Str::plural($moduleNameLower);

        switch ($filetype) {
            case 'command':
                $value = $moduleName.'Command.php';
                $filePath = str_replace('StubCommand.php', $value, $filePath);
                break;

            case 'database':
                $value = date('Y_m_d_his_').'create_'.$moduleNameLowerPlural.'_table.php';
                $filePath = str_replace('stubMigration.php', $value, $filePath);
                break;

            case 'factories':
                $value = $moduleName.'Factory.php';
                $filePath = str_replace('stubFactory.php', $value, $filePath);
                break;

            case 'seeders':
                $value = $moduleName.'DatabaseSeeder.php';
                $filePath = str_replace('stubSeeders.php', $value, $filePath);
                break;

            case 'models':
                $value = $moduleName.'.php';
                $filePath = str_replace('stubModel.php', $value, $filePath);
                break;

            case 'providers':
                $value = $moduleName.'ServiceProvider.php';
                $filePath = str_replace('stubServiceProvider.php', $value, $filePath);
                break;

            case 'controller_backend':
                $value = $moduleNamePlural.'Controller.php';
                $filePath = str_replace('stubBackendController.php', $value, $filePath);
                break;

            case 'controller_frontend':
                $value = $moduleNamePlural.'Controller.php';
                $filePath = str_replace('stubFrontendController.php', $value, $filePath);
                break;

            case 'views_backend_index':
                $value = $moduleNameLowerPlural;
                $filePath = str_replace('stubViews', $value, $filePath);
                break;

            case 'views_backend_index_datatable':
                $value = $moduleNameLowerPlural;
                $filePath = str_replace('stubViews', $value, $filePath);
                break;

            case 'views_backend_create':
                $value = $moduleNameLowerPlural;
                $filePath = str_replace('stubViews', $value, $filePath);
                break;

            case 'views_backend_form':
                $value = $moduleNameLowerPlural;
                $filePath = str_replace('stubViews', $value, $filePath);
                break;

            case 'views_backend_show':
                $value = $moduleNameLowerPlural;
                $filePath = str_replace('stubViews', $value, $filePath);
                break;

            case 'views_backend_edit':
                $value = $moduleNameLowerPlural;
                $filePath = str_replace('stubViews', $value, $filePath);
                break;

            case 'views_backend_trash':
                $value = $moduleNameLowerPlural;
                $filePath = str_replace('stubViews', $value, $filePath);
                break;

            case 'views_frontend_index':
                $value = $moduleNameLowerPlural;
                $filePath = str_replace('stubViews', $value, $filePath);
                break;

            case 'views_frontend_show':
                $value = $moduleNameLowerPlural;
                $filePath = str_replace('stubViews', $value, $filePath);
                break;

            default:
                // code...
                break;
        }

        return $filePath;
    }

    public function enableModule($moduleName)
    {
        $destination = base_path('modules_statuses.json');

        $content = (File::exists($destination)) ? File::get($destination) : '{}';

        File::put('modules_statuses.json', json_encode(array_merge(json_decode($content, true), [$moduleName => true]), JSON_PRETTY_PRINT));

        $this->components->info("{$moduleName} - Module Created Successfully!");
    }
}
