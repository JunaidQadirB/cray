<?php

namespace MoonBear\LaravelCrudScaffold\Console\Commands;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use MoonBear\LaravelCrudScaffold\Console\Contracts\GeneratorCommand;

class MakeScaffold extends GeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'mbt:scaffold';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mbt:scaffold {name : Model name. Controller, factory, migration, views will be based on this name.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold a nearly complete boilerplate CRUD pages for the specified Model';

    /**
     * Create a new command instance.
     *
     */
    /*    public function __construct()
        {
            parent::__construct();
        }*/

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
         * Steps
         * - Generate Model
         * - Generate Factory
         * - Generate Migration
         * - Generate Controller
         * - Generate Requests
         * - Generate Views
         *
         */
        $this->type = 'Model';

        $this->createFactory();

        $this->createMigration();

        $this->createController();

        $this->createViews();

        $this->type = 'Request';

        $this->createRequest('Store');

        $this->createRequest('Update');


    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return resource_path('stubs/' . str_slug($this->type) . '.stub');
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createFactory()
    {
        $factory = Str::studly(class_basename($this->argument('name')));

        $this->call('mbt:factory', [
            'name'    => "{$factory}Factory",
            '--model' => $this->argument('name'),
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration()
    {
        $table = Str::plural(Str::snake(class_basename($this->argument('name'))));

        $this->call('mbt:migration', [
            'name'     => "create_{$table}_table",
            '--create' => $table,
        ]);
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController()
    {

        $controller = Str::studly(class_basename($this->argument('name')));

        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('mbt:controller', [
            'name'    => "{$controller}Controller",
            '--model' => $modelName,
        ]);
    }

    /**
     * Create a controller for the model.
     *
     * @param string $requestType
     *
     * @return void
     */
    protected function createRequest($requestType = 'Store')
    {
        $model = $this->getNameInput();
        $name  = "{$model}{$requestType}Request";
        $this->call('mbt:request', [
            'name'    => $name,
            '--model' => $model,
            '--type'  => str_slug($requestType),
        ]);
    }

    protected function createViews()
    {
        $name = $this->argument('name');

        $this->call('mbt:view', [
            'name'  => $name,
            '--all' => true,
        ]);


    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        switch ($this->type) {
            case 'Request':
                return $rootNamespace . '\Http\Requests';
        }

        return $rootNamespace;
    }

}
