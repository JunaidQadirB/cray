<?php

namespace MoonBear\LaravelCrudScaffold\Console\Commands;


use Config;
use Illuminate\Support\Str;
use MoonBear\LaravelCrudScaffold\Console\Contracts\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ViewMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'mbt:view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new View';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    private $fileName = 'index';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        /*  if (parent::handle() === false && ! $this->option('force')) {
              return;
          }*/
        if ( ! $this->option('index') && ! $this->option('create') && ! $this->option('edit') && ! $this->option('all')) {
            $this->input->setOption('index', true);
        }
        $this->createView();

    }

    /**
     * Create a view for the model.
     *
     * @return void
     */
    protected function createView()
    {
        $viewDirSlug = Str::slug(Str::plural(str_to_words($this->argument('name')), 2));
        $path        = Config::get('view.paths')[0] . '/' . $viewDirSlug;

        $this->createViewDirectory();

//        $this->input->setOption('all', true);

        if ($this->option('all')) {
            $this->input->setOption('index', true);
            $this->input->setOption('create', true);
            $this->input->setOption('edit', true);
            $this->input->setOption('show', true);
        }

        if ($this->option('index')) {
            $this->buildView('index', $path);
            $this->createDeleteView($path);
        }

        if ($this->option('create')) {
            $this->buildView('create', $path);
        }

        if ($this->option('edit')) {
            $this->buildView('edit', $path);
        }

        if ($this->option('show')) {
            $this->buildView('show', $path);
        }
    }

    /**
     *
     */
    protected function createViewDirectory()
    {
        $name        = $this->argument('name');
        $viewDirSlug = Str::slug(Str::plural(str_to_words($name), 2));
        $path        = Config::get('view.paths')[0] . '/' . $viewDirSlug;
        if ( ! file_exists($path)) {
            mkdir($path, 0777);
        } else {
            $this->warn($viewDirSlug . ' exists. Ignoring');
        }
    }

    protected function buildView($type, $path)
    {
        $name           = $this->argument('name');
        $this->fileName = $type;
        $stub           = $this->files->get($this->getStub());
        $viewLabel      = str_plural(str_to_words($name), 2);
        $viewName       = Str::camel($viewLabel);
        $stub           = $this->replacePlaceholders($stub, $name, $path);
        $target         = $path . '/' . $type . '.blade.php';


        if ($dir = $this->option('dir')) {
            if ( ! file_exists($path . '/' . $dir)) {
                mkdir($path . '/' . $dir . '/', 0777, true);
            }
            $target = $path . '/' . $dir . '/' . $type . '.blade.php';
        }

        if (file_exists($target) && ! $this->option('force')) {
            $this->error("File already exists. Cannot overwrite {$target}.");
        } else {
            file_put_contents($target, $stub);
            $this->info("View successfully created in {$target}");
        }
        /**
         * Create the _form partial form the stub
         */
        $formPartial = $path . '/_form.blade.php';
        $formStub = $this->files->get($this->getStub('_form'));

        if (file_exists($formPartial) && !$this->option('force')) {
            $this->error("File already exists. Cannot overwrite {$formPartial}.");
        } else {
            file_put_contents($formPartial, $formStub);
            $this->info("View successfully created in {$formPartial}");
        }
    }

    /**
     * Replace all placeholders
     *
     * @param $stub
     * @param $name
     * @param null $path
     *
     * @return mixed
     */
    protected function replacePlaceholders($stub, $name, $path = null)
    {
        $modelSlug = Str::slug(Str::plural(str_to_words($name), 2));

        $viewLabel = str_to_words($name);
        $stub      = str_replace('$label$', $viewLabel, $stub);

        $viewLabelPlural = str_plural(str_to_words($name));
        $stub = str_replace('$labelPlural$', $viewLabelPlural, $stub);

        $viewName = Str::camel($name);
        $stub     = str_replace('$name$', $viewName, $stub);

        $stub = str_replace('$model$', $name, $stub);
        $stub = str_replace('$modelSlug$', $modelSlug, $stub);

        $stub = str_replace('$rows$', '$' . camel_case(str_plural($name, 2)), $stub);
        $stub = str_replace('$row$', '$' . camel_case($name), $stub);

        return $stub;
    }


    /**
     * Get the stub file for the generator.
     *
     * @param null|string $fileName
     * @return string
     */
    protected function getStub($fileName = null)
    {
        if (isset($fileName)) {
            $this->fileName = $fileName;
        }
        return resource_path("stubs/view/{$this->fileName}.stub");
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
        return $rootNamespace;
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the model'],
        ];
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
                'all',
                'a',
                InputOption::VALUE_NONE,
                'Generate an index, create, and an edit view for the model',
            ],

            ['index', 'i', InputOption::VALUE_NONE, 'Create a only the index view for the model'],

            ['create', 'c', InputOption::VALUE_NONE, 'Create only the create view for the model'],

            ['edit', 'e', InputOption::VALUE_NONE, 'Create only the edit view for the model'],

            ['show', 's', InputOption::VALUE_NONE, 'Create only the show view for the model'],

            ['force', 'f', InputOption::VALUE_NONE, 'Create the file even if the file already exists.'],

            ['dir', 'd', InputOption::VALUE_OPTIONAL, 'Create the file inside this directory within the view.'],
        ];
    }

    protected function createDeleteView($path)
    {
        $this->input->setOption('dir', 'modals');
        $this->buildView('delete', $path);
        $this->input->setOption('dir', false);
    }
}
