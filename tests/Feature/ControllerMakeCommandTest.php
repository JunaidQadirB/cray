<?php

namespace JunaidQadirB\Cray\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use JunaidQadirB\Cray\Tests\TestCase;

class ControllerMakeCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->removeGeneratedFiles();
    }

    public function test_it_throws_exception_when_a_name_is_not_passed()
    {
        $this->expectException(\Symfony\Component\Console\Exception\RuntimeException::class);
        $this->artisan('cray:controller');
    }

    public function test_it_creates_a_controller_with_the_given_name()
    {
        $this->removeGeneratedFiles();
        //Make sure no artifact related to Post exists
        $this->assertFileDoesNotExist(app_path('Post.php'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/PostController.php'));

        $this->artisan('cray:controller PostController');

        $this->assertFileExists(app_path('Post.php'));
        $this->assertFileExists(app_path('Http/Controllers/PostController.php'));
    }

    public function test_it_gives_an_error_if_controller_exists()
    {
        $this->artisan('cray:controller PostController');
        $this->artisan('cray:controller PostController');
        $output = Artisan::output();
        $this->assertSame('Controller already exists!'.PHP_EOL, $output);
    }

    public function test_generates_a_resource_controller_for_the_given_model_if_models_directory_does_not_exist()
    {
        $this->removeGeneratedFiles();

        $this->assertDirectoryDoesNotExist(app_path('Models'));
        $this->assertFileDoesNotExist(app_path('/Http/Controllers/PostController.php'));
        $this->assertFileDoesNotExist(app_path('Models/Post.php'));
        $this->assertFileDoesNotExist(app_path('Post.php'));

        $this->artisan('cray:controller PostController --model=Post');

        $this->assertFileExists(app_path('/Http/Controllers/PostController.php'));
        $this->assertFileExists(app_path('Post.php'));
        $this->assertDirectoryDoesNotExist(app_path('Models'));
        $this->assertFileDoesNotExist(app_path('Models/Post.php'));
    }

    public function test_generates_a_resource_controller_for_the_given_model_if_models_directory_exists()
    {
        mkdir(app_path('Models'));

        $this->assertDirectoryExists(app_path('Models'));
        $this->assertFileDoesNotExist(app_path('/Http/Controllers/PostController.php'));
        $this->assertFileDoesNotExist(app_path('Models/Post.php'));

        $this->artisan('cray:controller PostController --model=Post');

        $this->assertFileExists(app_path('/Http/Controllers/PostController.php'));
        $this->assertFileExists(app_path('Models/Post.php'));
    }

    public function test_it_uses_views_path_specified_in_views_dir_option_scenario1()
    {
        $this->removeGeneratedFiles();
        //Scenario 1
        $this->artisan('cray:controller PostController --model=Post --views-dir=posts');
        $controllerContents = file_get_contents(app_path('/Http/Controllers/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return view('posts.index', compact('posts'));", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'posts.edit'", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'posts.show'", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'posts.create'", $controllerContents);
    }

    public function test_it_uses_views_path_specified_in_views_dir_option_scenario2()
    {
        $this->removeGeneratedFiles();

        //Scenario 2
        $this->artisan('cray:controller PostController --model=Post --views-dir=blog_posts');
        $controllerContents = file_get_contents(app_path('/Http/Controllers/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return view('blog_posts.posts.index', compact('posts'));", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog_posts.posts.edit'", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog_posts.posts.show'", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog_posts.posts.create'", $controllerContents);
    }

    public function test_it_uses_views_path_specified_in_views_dir_option_scenario3()
    {
        $this->removeGeneratedFiles();

        //Scenario 3
        $this->artisan('cray:controller PostController --model=Post --views-dir=blog/posts');
        $controllerContents = file_get_contents(app_path('/Http/Controllers/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return view('blog.posts.index', compact('posts'));", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog.posts.edit'", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog.posts.show'", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog.posts.create'", $controllerContents);
    }

    public function test_it_uses_views_path_specified_in_views_dir_option_scenario4()
    {
        $this->removeGeneratedFiles();

        //Scenario 4
        $this->artisan('cray:controller PostController --model=Models/Post --views-dir=blog/posts');

        $controllerContents = file_get_contents(app_path('/Http/Controllers/PostController.php'));

        $this->assertStringContainsStringIgnoringCase("return view('blog.posts.index', compact('posts'));", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog.posts.edit'", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog.posts.show'", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog.posts.create'", $controllerContents);
    }

    public function test_it_uses_views_path_specified_in_views_dir_option_scenario5()
    {
        $this->removeGeneratedFiles();

        $this->artisan('cray:controller PostController --model=Models/Post --controller-dir=dashboard --views-dir=dashboard/system');

        $createBladeView = file_get_contents(resource_path('views/dashboard/system/posts/create.blade.php'));
        $postController = file_get_contents(app_path('Http/Controllers/Dashboard/PostController.php'));

        $this->assertStringContainsString("@include('dashboard.system.posts._form')", $createBladeView, 'Include path is incorrect');
        $this->assertStringContainsString("return view('dashboard.system.posts.index'", $postController, 'View path is incorrect');
    }

    public function test_it_uses_the_specified_route_or_falls_back_to_model_slug()
    {
        $this->removeGeneratedFiles();

        //Scenario 1
        /*$this->artisan('cray:controller PostController --model=Post --views-dir=posts --route-base=my-posts');
        $controllerContents = file_get_contents(app_path('/Http/Controllers/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return \$this->success('Post added successfully!', 'my-posts.index');", $controllerContents);*/

//        unlink(app_path('Http/Controllers/PostController.php'));

        //Scenario 1
        $this->artisan('cray:controller PostController --model=Post --views-dir=posts');
        $controllerContents = file_get_contents(app_path('/Http/Controllers/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return \$this->success('Post added successfully!', 'posts.index');", $controllerContents);
    }

    public function test_it_creates_the_controller_in_the_specified_base()
    {
        $this->removeGeneratedFiles();

        $this->assertFileDoesNotExist(base_path('Modules/blog/src/Post.php'));
        $this->assertFileDoesNotExist(base_path('Modules/blog/src/Http/Controllers/PostController.php'));

        $this->artisan('cray:controller PostController --model=Post --base=Modules/blog');

        $this->assertFileExists(base_path('Modules/blog/src/Post.php'));
        $this->assertFileExists(base_path('Modules/blog/src/Http/Controllers/PostController.php'));
    }

    public function test_it_creates_the_controller_in_the_specified_base_with_custom_controller_dir()
    {
        $this->removeGeneratedFiles();

        $this->assertFileDoesNotExist(base_path('Modules/blog/src/Post.php'));
        $this->assertFileDoesNotExist(base_path('Modules/blog/src/Http/Controllers/Dashboard/PostController.php'));

        $this->artisan('cray:controller PostController --model=Post --base=Modules/blog --controller-dir=dashboard ');

        $this->assertFileExists(base_path('Modules/blog/src/Post.php'));
        $this->assertFileExists(base_path('Modules/blog/src/Http/Controllers/Dashboard/PostController.php'));

        $this->assertStringContainsString("view('posts::posts", file_get_contents(base_path('Modules/blog/src/Http/Controllers/Dashboard/PostController.php')));
    }

    public function test_it_creates_the_controller_in_the_specified_base_with_custom_controller_dir_and_namespace()
    {
        $this->removeGeneratedFiles();

        $this->assertFileDoesNotExist(base_path('Modules/blog/src/Post.php'));
        $this->assertFileDoesNotExist(base_path('Modules/blog/src/Http/Controllers/Dashboard/PostController.php'));

        $this->artisan('cray:controller PostController --model=Post --base=Modules/blog --namespace=My/Blog/ --controller-dir=dashboard ');

        $this->assertFileExists(base_path('Modules/blog/src/Post.php'));
        $this->assertFileExists(base_path('Modules/blog/src/Http/Controllers/Dashboard/PostController.php'));

        $expectedNamespace = 'namespace My\Blog\Http\Controllers\Dashboard;';
        $this->assertStringContainsStringIgnoringCase($expectedNamespace, file_get_contents(base_path('Modules/blog/src/Http/Controllers/Dashboard/PostController.php')));
    }

    public function test_it_creates_the_controller_in_the_specified_directory_namespace()
    {
        $this->removeGeneratedFiles();

        $this->assertFileDoesNotExist(app_path('Post.php'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/Dashboard/PostController.php'));

        $this->artisan('cray:controller PostController --model=Post --controller-dir=Dashboard');

        $this->assertFileExists(app_path('Post.php'));
        $this->assertFileExists(app_path('Http/Controllers/Dashboard/PostController.php'));

        $this->removeGeneratedFiles();
        if (! file_exists(app_path('Models'))) {
            mkdir(app_path('Models'));
        }

        $this->assertFileDoesNotExist(app_path('Models/Post.php'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/Dashboard/PostController.php'));

        $this->artisan('cray:controller PostController --model=Models/Post --controller-dir=Dashboard');

        $this->assertFileExists(app_path('Models/Post.php'));
        $this->assertFileExists(app_path('Http/Controllers/Dashboard/PostController.php'));
    }

    public function test_it_creates_the_controller_with_the_specified_route_base()
    {
        $this->removeGeneratedFiles();
        $this->artisan('cray:controller PostController --model=Post --controller-dir=Dashboard --route-base=dashboard.posts');
        $controllerContents = file_get_contents(app_path('/Http/Controllers/Dashboard/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return \$this->success('Post added successfully!', 'dashboard.posts.index');", $controllerContents);
    }
}
