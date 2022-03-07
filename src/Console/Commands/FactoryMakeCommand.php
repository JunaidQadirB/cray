<?php

namespace JunaidQadirB\Cray\Console\Commands;

use JunaidQadirB\Cray\Console\Contracts\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class FactoryMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cray:factory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model factory';

    protected $signature = 'cray:factory {name}
    {-m|--model= : Name of the model}
    {-b|--base= : Base to create paths from}
    {-n|--namespace= : Namespace to use}
    {-f|--force : Overwrite if factory class already exists}';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Factory';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return config('cray.stubs_dir').'/factory.stub';
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());
        $name = $this->argument('name');

        return str_replace(
            'DummyClass',
            $name,
            $stub
        );
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getPath($name)
    {
        $name = str_replace(
            ['\\', '/'],
            '',
            $this->argument('name')
        );

        $path = base_path($this->option('base'));

        return $this->getDatabasePath($path)."/factories/{$name}.php";
    }

    private function getDatabasePath($path): string
    {
        if($this->option('base')){
            return base_path($this->option('base')).'/database';
        }

        return database_path();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The name of the model'],
        ];
    }
}
