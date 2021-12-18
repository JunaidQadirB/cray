<?php


namespace JunaidQadirB\Cray\Tests;

use Illuminate\Support\Facades\Artisan;
use JunaidQadirB\Cray\CrayServiceProvider;
use Orchestra\Testbench\TestCase as Testbench;

class TestCase extends Testbench
{
    public function removeGeneratedFiles()
    {
        if(file_exists(base_path('routes/web.php'))){
            unlink(base_path('routes/web.php'));
            file_put_contents(base_path('routes/web.php'),"<?php\n\n");
        }

        if (file_exists(app_path('Http/Controllers/PostController.php'))) {
            unlink(app_path('Http/Controllers/PostController.php'));
        }

        if (file_exists(app_path('Http/Controllers/Dashboard/PostController.php'))) {
            unlink(app_path('Http/Controllers/Dashboard/PostController.php'));
        }

        if (file_exists(app_path('Http/Requests/PostUpdateRequest.php'))) {
            unlink(app_path('Http/Requests/PostUpdateRequest.php'));
        }

        if (file_exists(app_path('Http/Requests/PostStoreRequest.php'))) {
            unlink(app_path('Http/Requests/PostStoreRequest.php'));
        }

        if (file_exists(app_path('Post.php'))) {
            unlink(app_path('Post.php'));
        }

        if (file_exists(app_path('Models'))) {
            $this->rmdirRecursive(app_path('Models'));
        }

        if (file_exists(app_path('Models'))) {

            rmdir('Models');
        }

        if (file_exists(resource_path('views/posts'))) {
            $this->rmdirRecursive(resource_path('views/posts'));
        }

        if (file_exists(resource_path('views/dashboard/posts'))) {

            $this->rmdirRecursive(resource_path('views/dashboard/posts'));
        }

        if (file_exists(base_path('database/factories/PostFactory.php'))) {
            unlink(base_path('database/factories/PostFactory.php'));
        }

        $migrations = glob(base_path('database/migrations/*.php'));
        foreach ($migrations as $migration) {
            unlink($migration);
        }

        if (file_exists(base_path('database/factories/PostFactory.php'))) {
            unlink(base_path('database/factories/PostFactory.php'));
        }
    }

    public function deleteStubs()
    {
        if (file_exists(resource_path('stubs'))) {
            $stubs = glob(resource_path('stubs') . '/*.stub');
            foreach ($stubs as $stub) {
                if (!file_exists($stub)) {
                    continue;
                }
                unlink($stub);
            }
            $stubs = glob(resource_path('stubs') . '/view/*.stub');
            foreach ($stubs as $stub) {
                if (!file_exists($stub)) {
                    continue;
                }
                unlink($stub);
            }
            rmdir(resource_path('stubs/view'));
            rmdir(resource_path('stubs'));
        }
    }

    protected function getPackageProviders($app)
    {
        return [CrayServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        Artisan::call("vendor:publish", ["--tag" => "cray"]);

    }

    protected function resolveApplicationConsoleKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Console\Kernel', 'JunaidQadirB\Cray\Console\Kernel');
    }

    function rmdirRecursive($dir)
    {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $file = "$dir/$file";
            if (!file_exists($file)) {
                continue;
            }
            if (is_dir($file) && file_exists($file)) {
                $this->rmdirRecursive($file);
            } elseif (file_exists($file)) {
                unlink($file);
            }
        }
        if (!file_exists($dir)) {
            return;
        }
        rmdir($dir);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMockingConsoleOutput();

        $this->removeGeneratedFiles();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (file_exists(resource_path('views/posts'))) {
//            $this->rmdirRecursive(resource_path('views/posts'));
        }
    }
}
