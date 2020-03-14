<?php


namespace JunaidQadirB\Cray\Tests;

use Illuminate\Support\Facades\Artisan;
use JunaidQadirB\Cray\CrayServiceProvider;
use Orchestra\Testbench\TestCase as Testbench;

class TestCase extends Testbench
{
    public function removeGeneratedFiles()
    {
        if (file_exists(app_path('Http/Controllers/PostController.php'))) {
            unlink(app_path('Http/Controllers/PostController.php'));
        }

        if (file_exists(app_path('Http/Controllers/Dashboard/PostController.php'))) {
            unlink(app_path('Http/Controllers/Dashboard/PostController.php'));
        }

        if (file_exists(app_path('Post.php'))) {
            unlink(app_path('Post.php'));
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
            if ($file == '.' OR $file == '..') continue;

            $file = "$dir/$file";
            if (!file_exists($file)) {
                continue;
            }
            if (is_dir($file) && file_exists($file)) {
                $this->rmdirRecursive($file);
                if (file_exists($file)) {
                    rmdir($file);
                }

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
        if (file_exists(resource_path('views/posts'))) {
            $this->rmdirRecursive(resource_path('views/posts'));
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (file_exists(resource_path('views/posts'))) {
//            $this->rmdirRecursive(resource_path('views/posts'));
        }
    }
}
