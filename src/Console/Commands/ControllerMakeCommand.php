<?php

namespace MoonBear\LaravelCrudScaffold\Console\Commands;


use Illuminate\Support\Str;
use InvalidArgumentException;
use MoonBear\LaravelCrudScaffold\Console\Contracts\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'mbt:controller';

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
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Controllers';
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        if ($this->option('parent')) {
            $replace = $this->buildParentReplacements();
        }

        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);

            if ($model = $this->option('model')) {
                $replace = str_replace('$modelSlug$', Str::slug(str_to_words($model), '-'), $replace);
            }
        }


        $replace["use {$controllerNamespace}\Controller;\n"] = '';

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
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
            if ($this->confirm("A {$parentModelClass} model does not exist. Do you want to generate it?", true)) {
                $this->call('mbt:model', ['name' => $parentModelClass]);
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
     *
     * @return string
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        $model = trim(str_replace('/', '\\', $model), '\\');

        if (!Str::startsWith($model, $rootNamespace = $this->laravel->getNamespace())) {
            $model = $rootNamespace . $model;
        }

        return $model;
    }

    /**
     * Build the model replacement values.
     *
     * @param array $replace
     *
     * @return array
     */
    protected function buildModelReplacements(array $replace)
    {
        $modelClass = $this->parseModel($this->option('model'));

        if (!class_exists($modelClass)) {
            /*if ($this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {

            }*/
            $this->call('mbt:model', ['name' => $modelClass]);
        }

        $label = str_to_words(class_basename($modelClass));

        $modelSlug = Str::slug(Str::plural($label, 2));
        $viewsDir = '';
        if ($viewsDir = $this->option('views-dir')) {
            $viewsDir = $viewsDir . '.';
        }
        return array_merge($replace, [
            'DummyFullModelClass' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            'DummyModelVariable' => lcfirst(class_basename($modelClass)),
            '$modelSlug$' => $modelSlug,
            '$label$' => $label,
            '$rows$' => Str::plural(lcfirst(class_basename($modelClass)), 2),
            '$dir$' => $viewsDir
        ]);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generate a resource controller for the given model.'],

            ['resource', 'r', InputOption::VALUE_NONE, 'Generate a resource controller class.'],

            ['parent', 'p', InputOption::VALUE_OPTIONAL, 'Generate a nested resource controller class.'],

            ['api', null, InputOption::VALUE_NONE, 'Exclude the create and edit methods from the controller.'],

            ['views-dir', 'i', InputOption::VALUE_OPTIONAL, 'Specify the view path within the views directory'],

            ['controller-dir', 'c', InputOption::VALUE_OPTIONAL, 'Specify the controller path within the Http directory'],
        ];
    }
}
