<?php

namespace JunaidQadirB\Cray\Console\Contracts;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Support\Str;

use function base_path;

abstract class GeneratorCommand extends \Illuminate\Console\GeneratorCommand
{

    /**
     * @var array|string|string[]
     */
    public $class;


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        // First we need to ensure that the given name is not a reserved word within the PHP
        // language and that the class name will actually be valid. If it is not valid we
        // can error now and prevent from polluting the filesystem using invalid files.
        if ($this->isReservedName($this->getNameInput())) {
            $this->error('The name "'.$this->getNameInput().'" is reserved by PHP.');

            return false;
        }

        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath($name);

        // Next, We will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already exists!');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $displayPath = str_replace($this->laravel->basePath(), '', $path);

        $this->info($this->type.' created successfully in '.$displayPath);

        if (in_array(CreatesMatchingTest::class, class_uses_recursive($this))) {
            $this->handleTestCreation($path);
        }
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return is_dir(app_path('Models')) ? $rootNamespace.'\\Models' : $rootNamespace;
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     *
     * @return bool
     */


    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $path = '';
        if($base = $this->option('base')){
            return $this->getArtifactPath($base);
        }

        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $base = $this->option('base') ?? $this->laravel['path'];
        $file = '/'.str_replace(array('\\', '\\'), array('/', ''), $name).'.php';

        return $base.$file;
    }

    public function getArtifactPath($base)
    {
        $path = '';

        switch ($this->type){
            case 'Controller':
                $path = $base .'/Http/Controllers/'.$this->argument('name').'.php';
                if($controllerSubDir= ($this->hasOption('controller-dir') &&  $this->option('controller-dir'))){
                    $path = $base.'/Http/Controllers/'.$controllerSubDir.'/'.$this->argument('name').'.php';
                }
                break;
            case 'Model':
                $model = class_basename($this->argument('name'));
                $path = $base .'/Models/'.$model.'.php';

                break;
        }

        return $this->laravel->basePath($path);
    }
    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     *
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);
        $this->class = $name.'::class';
        return str_replace('DummyClass', $class, $stub);
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     *
     * @return string
     */
    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }



    /**
     * Get the desired class name from the input.
     *
     * @return string
     */


    protected function getRelativePath($path)
    {
        return str_replace(base_path().'/', '', $path);
    }

    /**
     * Add route for the generated resource to the relevant routes file
     * @param  string  $route
     * @param  string  $controllerClassPath
     */
    public function addRoute(
        string $route,
        string $controllerClassPath
    ) {

        $routeFile = base_path('routes/web.php');
        if (!file_exists($routeFile)) {
            file_put_contents($routeFile, <<<DATA
<?php


DATA
            );
        }
        $routeContent = file_get_contents($routeFile);
        $route = str_replace('.', '/', $route);

        $routeToAdd = "Route::resource('".$route."', ".$controllerClassPath.");\n";

        if (strpos($routeContent, $routeToAdd) === false) {
            file_put_contents($routeFile, $routeToAdd, FILE_APPEND);
            $this->info("Route added successfully!");
            $this->info("Click to open: ".url($route));
        }
    }


    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->option('namespace') ?? $this->laravel->getNamespace();
    }

    public function getCommonArguments()
    {
        $arguments=[];

        if( $this->option('base')){
            $arguments['--base'] = $this->option('base');
        }

        if($this->hasOption('force') && $this->option('force') !== false){
            $arguments['--force'] = $this->option('force');
        }

        if( $this->option('namespace')){
            $arguments['--namespace'] = $this->option('namespace');
        }

        if ($this->option('route-base') ) {
            $arguments['--route-base'] = $this->option('route-base');
        } elseif ($this->option('views-dir')) {
            $arguments['--route-base'] = $this->option('views-dir');
        }

        if ($this->option('views-dir')) {
            $arguments['--views-dir'] = $this->option('views-dir');
        }

        $controllerDir = $this->option('controller-dir');
        if ($controllerDir) {
            $arguments['--controller-dir'] = $controllerDir;
        }

        return $arguments;
    }
}
