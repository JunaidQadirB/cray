<?php

namespace JunaidQadirB\Cray\Console\Commands;

use Illuminate\Support\Str;
use JunaidQadirB\Cray\Console\Contracts\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends GeneratorCommand
{
    /**
     * @var mixed|string
     */
    public $routeBase;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cray:controller';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class';
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    public function handle()
    {
        parent::handle();

        if ($this->type === 'Controller') {
            $name = str_replace('Controller', '', $this->argument('name'));
            $this->routeBase = $this->option('route-base') ??
                Str::plural($name);

            $this->class = $this->qualifyClass($this->argument('name'))
                . '::class';

            $this->addRoute($this->routeBase, $this->class);
        }
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param string $name
     * @return string
     */
    protected function buildClass($name)
    {
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        if ($this->option('parent')) {
            $replace = $this->buildParentReplacements();
        }

        $replace = $this->buildModelReplacements($replace);

        if ($this->model) {
            if ($model = $this->model) {
                $replace = str_replace('$modelSlug$',
                    Str::slug(str_to_words($model), '-'), $replace);
            }
        }

        $replace["use {$controllerNamespace}\Controller;\n"] = '';

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    /**
     * Build the replacements for a parent controller.
     *
     * @return array
     */
    protected function buildParentReplacements()
    {
        $parentModelClass = $this->parseModel($this->option('parent'));

        if (!class_exists($parentModelClass)) {
            if ($this->confirm("A {$parentModelClass} model does not exist. Do you want to generate it?",
                true)
            ) {
                $this->call('cray:model', ['name' => $parentModelClass]);
            }
        }

        return [
            'ParentDummyFullModelClass' => $parentModelClass,
            'ParentDummyModelClass' => class_basename($parentModelClass),
            'ParentDummyModelVariable' => lcfirst(class_basename($parentModelClass)),
        ];
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param string $model
     * @return string
     */
    protected function parseModel($model)
    {
        /**
         * Check if Models directory exist in /app
         * Check if model has a namespace.
         */
        $model = str_replace('Controller', '', class_basename($model));

        $rootNamespace = $this->hasOption('namespace')
        && $this->option('namespace')
            ? $this->option('namespace')
            : $this->laravel->getNamespace();

        $namespace = is_dir(app_path('Models')) ? $rootNamespace . 'Models\\'
            : $rootNamespace;

        $model = str_replace('/', '\\', $model);
        if (!Str::startsWith($model, $rootNamespace)) {
            $model = $namespace . $model;
        }

        return $model;
    }

    /**
     * Build the model replacement values.
     *
     * @param array $replace
     * @return array
     */
    protected function buildModelReplacements(array $replace)
    {
        $this->model = str_replace('Controller', '', $this->argument('name'));

        if ($this->option('model')) {
            $this->model = $this->option('model');
        }

        $modelClass = $this->parseModel($this->model);

        if (!class_exists($modelClass)) {
            /*if ($this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {

            }*/

            $arguments = [
                'name' => class_basename($this->model),
                '--base' => $this->option('base'),
                '--namespace' => $this->option('namespace'),
            ];

            $this->call('cray:model', $arguments);
        }
        $label = str_to_words(class_basename($modelClass));

        $modelSlug = Str::slug(Str::plural($label, 2));

        $dir = $this->hasOption('views-dir')
            ? $this->option('views-dir')
            : $this->appendModelToViewDir($this->option('views-dir'),
                $modelSlug);

        $dir = str_replace('/', '.', $dir);
        $dir = ltrim($dir, '.');

        if ($dir === '') {
            $dir = $modelSlug;
        }

        if ($this->option('base')) {
            $dir = $modelSlug . '::' . $dir;
        }

        $routeBase = $this->option('route-base') ?? $modelSlug;

        $this->routeBase = $routeBase;

        return array_merge($replace, [
            'DummyFullModelClass' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            'DummyModelVariable' => lcfirst(class_basename($modelClass)),
            '$modelSlug$' => $modelSlug,
            '$label$' => $label,
            '$rows$' => Str::plural(lcfirst(class_basename($modelClass)), 2),
            '$viewDir$' => $dir,
            '$routeBase$' => $routeBase,
        ]);
    }

    private function appendModelToViewDir($path, $model)
    {
        $pathArray = explode('/', $path);
        if ($pathArray[count($pathArray) - 1] === $model) {
            return $path;
        }

        return rtrim($path, '/') . '/' . $model;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = null;

        if ($this->option('parent')) {
            $stub = 'stubs/controller.nested.stub';
        } else {
            if ($this->option('model')) {
                $stub = 'stubs/controller.model.stub';
            } else {
                if ($this->option('resource')) {
                    $stub = 'stubs/controller.stub';
                }
            }
        }

        if ($this->option('api') && is_null($stub)) {
            $stub = 'stubs/controller.api.stub';
        } else {
            if ($this->option('api') && !is_null($stub)) {
                $stub = str_replace('.stub', '.api.stub', $stub);
            }
        }

        $stub = $stub ?? 'stubs/controller.plain.stub';

        return resource_path($stub);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $dir = $this->hasOption('controller-dir')
        && trim($this->option('controller-dir')) != ''
            ? $this->option('controller-dir')
            : null;
        if ($dir) {
            return $rootNamespace . '\Http\Controllers\\'
                . Str::studly(strtolower($dir));
        }

        return $rootNamespace . '\Http\Controllers';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            [
                'model', 'm', InputOption::VALUE_OPTIONAL,
                'Generate a resource controller for the given model.',
            ],

            [
                'resource', 'r', InputOption::VALUE_NONE,
                'Generate a resource controller class.',
            ],

            [
                'parent', 'p', InputOption::VALUE_OPTIONAL,
                'Generate a nested resource controller class.',
            ],

            [
                'api', null, InputOption::VALUE_NONE,
                'Exclude the create and edit methods from the controller.',
            ],

            [
                'views-dir', 'i', InputOption::VALUE_OPTIONAL,
                'Use the specified path in controller actions to return the respective view',
            ],

            [
                'controller-dir', 'c', InputOption::VALUE_OPTIONAL,
                'Specify the controller path within the Http directory',
            ],

            [
                'route-base', 'rb', InputOption::VALUE_OPTIONAL,
                'Specify the base route to use',
            ],

            [
                'force', 'f', InputOption::VALUE_NONE,
                'Overwrite existing controller',
            ],

            [
                'base', 'b', InputOption::VALUE_OPTIONAL,
                'Base to generate the controller from',
            ],

            [
                'namespace', null, InputOption::VALUE_OPTIONAL,
                'Namespace to generate the controller from',
            ],
        ];
    }
}
