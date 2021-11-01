<?php

namespace JunaidQadirB\Cray\Console\Commands;

use Illuminate\Support\Str;
use JunaidQadirB\Cray\Console\Contracts\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class RequestMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cray:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Request';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if (!$this->option('type') || $this->option('type') == 'store') {
            return config('cray.stubs_dir') . '/request.stub';
        }

        return config('cray.stubs_dir') . '/request.update.stub';
    }

    protected function buildClass($name)
    {
        $replace = [];
        if ($model = $this->option('model')) {
            $model = Str::studly(class_basename($this->option('model')));
            $slug = Str::slug(str_to_words($model), '_');
            $replace['$modelSlug$'] = $slug;
            $replace['$modelTable$'] = Str::plural($slug, 2);
        }

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
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
        return $rootNamespace . '\Http\Requests';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'The given model.'],
            ['type', 't', InputOption::VALUE_OPTIONAL, 'Type of request. Values can be store or update'],
        ];
    }
}
