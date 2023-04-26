<?php

namespace JunaidQadirB\Cray\Console\Commands;

use Illuminate\Console\Command;

class DeleteResourceCommand extends Command
{
    public $resourceTypes = [
        'model' => [
            'key' => 'm',
            'name' => 'Model',
            'path' => 'app/Models',
        ],
        'controller' => [
            'key' => 'c',
            'name' => 'Controller',
            'path' => 'app/Controllers',
        ],
        'request' => [
            'key' => 'r',
            'name' => 'Request',
            'path' => 'app/Http/Requests',
        ],
        'view' => [
            'key' => 'v',
            'name' => 'View',
            'path' => 'resources/views',
        ],
        'config' => [
            'key' => 'cfg',
            'name' => 'Config',
            'path' => 'config',
        ],
        'migration' => [
            'key' => 'mig',
            'name' => 'Migration',
            'path' => 'database/migrations',
        ],
        'factory' => [
            'key' => 'f',
            'name' => 'Factory',
            'path' => 'database/factories',
        ],
    ];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cray:rm';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $this->modles = $this->getModels();

        $choices = $this->makeActions();

        $response = $this->choice('Enter your choice. Examples: a or c,m', $choices, null, 1, true);

        if (in_array('a', $response)) {
            echo 'All~';
        } else {
            $workOn = [];
            foreach ($response as $item) {
                $workOn[] = collect($this->resourceTypes)->where('key', '=', $item)->firstOrFail();
            }
        }

        $model = $this->anticipate('Select the model', $this->modles);
        foreach ($workOn as $item) {
            $this->process('delete'.$item['name'], $model);
        }
    }

    public function getModels()
    {
        $modules = $this->getModules();
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        $psrNamespaces = (array) data_get($composer, 'autoload.psr-4');
        $namespaces = array_merge($psrNamespaces, $modules);

        return \JunaidQadirB\Cray\Cray::getModels($namespaces);
    }

    public function getModules($sources = 'src'): array
    {
        $modules = data_get(json_decode(file_get_contents(base_path('studio.json')), true), 'paths');

        $namespaces = collect($modules)
            ->map(function ($module) {
                $namespace = str_replace('Modules/', '', $module);
                $namespace = explode('/', $namespace);

                foreach ($namespace as $item => $name) {
                    $namespace[$item] = ucwords($name);
                }
                $namespace = implode('/', $namespace);

                return $namespace;
            })
            ->map(function ($item) {
                $item .= '/';

                return str_replace('/', '\\', $item);
            })->toArray();

        foreach ($modules as $index => $module) {
            $modules[$index] = $module.DIRECTORY_SEPARATOR.$sources;
        }

        return array_combine($namespaces, $modules);
    }

    public function makeActions()
    {
        $resourceTypeActions = $this->resourceTypes;
        $resourceTypeActions['all'] = ['name' => 'All', 'key' => 'a'];

        $resourceTypeActionKeys = collect($resourceTypeActions)->pluck('key')->toArray();
        $resourceTypeActions = collect($resourceTypeActions)->pluck('name')->toArray();

        return array_combine($resourceTypeActionKeys, $resourceTypeActions);
    }

    public function process($callback, $parameter)
    {
        $this->$callback($parameter);
    }

    private function deleteModel($model)
    {
        dd($model);
        $this->info('Deleting model '.$model.'...');
    }

    private function deleteController($model)
    {
        $controllerNamespace = '\\App\\Http\\Controllers\\';
        $namespacePrefixes = [
            '\\App\\Models\\',
            '\\App\\',

        ];
        $name = $model;
        foreach ($namespacePrefixes as $namespacePrefix) {
            $name = str_replace($namespacePrefix, '', $name);
        }

        $controllerName = $controllerNamespace.$name.'Controller';
        $controllerFile = app_path('Http/Controllers/'.$name.'Controller.php');
        if (! file_exists($controllerFile)) {
            $this->error('Controller '.$controllerName.' does not exist.');
            exit(0);
        }
        $confirmation = $this->confirm('Are you sure you want to delete this controller?');
        dd($confirmation);
        dd(class_exists($name), $controllerName, $controllerFile, file_exists($controllerFile));
        dd($model);
    }

    private function deleteMigration($model)
    {
    }

    private function deleteRequest($model)
    {
    }

    private function deleteView($model)
    {
    }

    private function deleteFactory($model)
    {
    }
}
