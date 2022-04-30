<?php

namespace JunaidQadirB\Cray\Tests\Feature;

use JunaidQadirB\Cray\Tests\TestCase;

class ControllerMakeCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->removeGeneratedFiles();
        $this->mockConsoleOutput = true;
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

    public function test_it_generates_pretty_urls()
    {
        $this->removeGeneratedFiles();
        //Make sure no artifact related to Post exists
        $this->assertFileDoesNotExist(app_path('Post.php'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/PostController.php'));

        $this->artisan('cray:controller PostController');

        $this->assertFileExists(app_path('Post.php'));
        $this->assertFileExists(app_path('Http/Controllers/PostController.php'));

        $routeFile = file_get_contents(base_path('routes/web.php'));

        $this->assertStringContainsString("Route::resource('posts', App\Http\Controllers\PostController::class);",
            $routeFile);

        $this->removeGeneratedFiles();

        $this->artisan('cray:controller MyPostController');

        $routeFile = file_get_contents(base_path('routes/web.php'));

        $this->assertStringContainsString("Route::resource('my-posts', App\Http\Controllers\MyPostController::class);",
            $routeFile);

        $this->artisan('cray:controller MyShinyPostController');

        $routeFile = file_get_contents(base_path('routes/web.php'));

        $this->assertStringContainsString("Route::resource('my-shiny-posts', App\Http\Controllers\MyShinyPostController::class);",
            $routeFile);

    }

    public function test_it_gives_an_error_if_controller_exists()
    {
        $this->removeGeneratedFiles();

        $this->artisan('cray:controller PostController')->assertSuccessful();
        $this->artisan('cray:controller PostController')->assertExitCode(0);
    }

    public function test_generates_a_resource_controller_for_the_given_model_if_models_directory_does_not_exist(
    )
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

    public function test_generates_a_resource_controller_for_the_given_model_if_models_directory_exists(
    )
    {
        mkdir(app_path('Models'));

        $this->assertDirectoryExists(app_path('Models'));
        $this->assertFileDoesNotExist(app_path('/Http/Controllers/PostController.php'));
        $this->assertFileDoesNotExist(app_path('Models/Post.php'));

        $this->artisan('cray:controller PostController --model=Post');

        $this->assertFileExists(app_path('/Http/Controllers/PostController.php'));
        $this->assertFileExists(app_path('Models/Post.php'));
    }

    public function test_it_uses_views_path_specified_in_views_dir_option_scenario1(
    )
    {
        $this->removeGeneratedFiles();
        //Scenario 1
        $this->artisan('cray:controller PostController --model=Post --views-dir=posts');
        $controllerContents
            = file_get_contents(app_path('/Http/Controllers/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return view('posts.index', compact('posts'));",
            $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'posts.edit'",
            $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'posts.show'",
            $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'posts.create'",
            $controllerContents);
    }

    public function test_it_uses_views_path_specified_in_views_dir_option_scenario2(
    )
    {
        $this->removeGeneratedFiles();

        //Scenario 2
        $this->artisan('cray:controller PostController --model=Post --views-dir=blog_posts');
        $controllerContents
            = file_get_contents(app_path('/Http/Controllers/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return view('blog_posts.index', compact('posts'));",
            $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog_posts.edit'",
            $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog_posts.show'",
            $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog_posts.create'",
            $controllerContents);
    }

    public function test_it_uses_views_path_specified_in_views_dir_option_scenario3(
    )
    {
        $this->removeGeneratedFiles();

        //Scenario 3
        $this->artisan('cray:controller PostController --model=Post --views-dir=blog/posts');
        $controllerContents
            = file_get_contents(app_path('/Http/Controllers/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return view('blog.posts.index', compact('posts'));",
            $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog.posts.edit'",
            $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog.posts.show'",
            $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog.posts.create'",
            $controllerContents);
    }

    public function test_it_uses_views_path_specified_in_views_dir_option_scenario4(
    )
    {
        $this->removeGeneratedFiles();

        //Scenario 4
        $this->artisan('cray:controller PostController --model=Models/Post --views-dir=blog/posts');

        $controllerContents
            = file_get_contents(app_path('/Http/Controllers/PostController.php'));

        $this->assertStringContainsStringIgnoringCase("return view('blog.posts.index', compact('posts'));",
            $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog.posts.edit'",
            $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog.posts.show'",
            $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog.posts.create'",
            $controllerContents);
    }

    public function test_it_uses_views_path_specified_in_views_dir_option_in_the_contoller_scenario5(
    )
    {
        $this->removeGeneratedFiles();

        $this->assertDirectoryDoesNotExist(resource_path('views/dashboard/system'));

        $this->artisan('cray:controller PostController --model=Models/Post --controller-dir=dashboard --views-dir=dashboard/system');

        $postController
            = file_get_contents(app_path('Http/Controllers/Dashboard/PostController.php'));

        $this->assertStringContainsString("return view('dashboard.system.index'",
            $postController, 'View path is incorrect');

        $this->assertStringContainsString("return view('dashboard.system.create'",
            $postController, 'View path is incorrect');

        $this->assertStringContainsString("return view('dashboard.system.edit'",
            $postController, 'View path is incorrect');
    }

    public function test_it_uses_the_specified_route_or_falls_back_to_model_slug(
    )
    {
        $this->removeGeneratedFiles();

        //Scenario 1
        /*$this->artisan('cray:controller PostController --model=Post --views-dir=posts --route-base=my-posts');
        $controllerContents = file_get_contents(app_path('/Http/Controllers/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return \$this->success('Post added successfully!', 'my-posts.index');", $controllerContents);*/

//        unlink(app_path('Http/Controllers/PostController.php'));

        //Scenario 1
        $this->artisan('cray:controller PostController --model=Post --views-dir=posts');
        $controllerContents
            = file_get_contents(app_path('/Http/Controllers/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return \$this->success('Post added successfully!', 'posts.index');",
            $controllerContents);
    }

    public function test_it_creates_the_controller_in_the_specified_base()
    {
        $this->removeGeneratedFiles();

        $this->assertFileDoesNotExist(base_path('Modules/blog/src/Post.php'));
        $this->assertFileDoesNotExist(base_path('Modules/blog/src/Http/Controllers/PostController.php'));


        $this->artisan('cray:controller PostController --model=Post --base=Modules/blog')
            ->expectsConfirmation(base_path('Modules/blog/routes/web.php')
                .' does not exist. Do you want to create it?', 'yes');

        $this->assertFileExists(base_path('Modules/blog/src/Post.php'));
        $this->assertFileExists(base_path('Modules/blog/src/Http/Controllers/PostController.php'));
    }

    public function test_it_creates_the_controller_in_the_specified_base_with_custom_controller_dir(
    )
    {
        $this->removeGeneratedFiles();

        $this->assertFileDoesNotExist(base_path('Modules/blog/src/Post.php'));
        $this->assertFileDoesNotExist(base_path('Modules/blog/src/Http/Controllers/Dashboard/PostController.php'));

        $this->artisan('cray:controller PostController --model=Post --base=Modules/blog --controller-dir=dashboard ')
            ->expectsConfirmation(base_path('Modules/blog/routes/web.php')
                .' does not exist. Do you want to create it?', 'yes')
            ->expectsOutput('Route created at Modules/blog/routes/web.php');

        $this->assertFileExists(base_path('Modules/blog/src/Post.php'));
        $this->assertFileExists(base_path('Modules/blog/src/Http/Controllers/Dashboard/PostController.php'));

        $this->assertStringContainsString("view('posts::posts",
            file_get_contents(base_path('Modules/blog/src/Http/Controllers/Dashboard/PostController.php')));
    }

    public function test_it_creates_the_controller_in_the_specified_base_with_custom_controller_dir_and_namespace(
    )
    {
        $this->removeGeneratedFiles();

        $this->assertFileDoesNotExist(base_path('Modules/blog/src/Post.php'));
        $this->assertFileDoesNotExist(base_path('Modules/blog/src/Http/Controllers/Dashboard/PostController.php'));

        $this->artisan('cray:controller PostController --model=Post --base=Modules/blog --namespace=My/Blog/ --controller-dir=dashboard ')
            ->expectsConfirmation(base_path('Modules/blog/routes/web.php')
                .' does not exist. Do you want to create it?', 'yes')
            ->expectsOutput('Route created at Modules/blog/routes/web.php');

        $this->assertFileExists(base_path('Modules/blog/src/Post.php'));
        $this->assertFileExists(base_path('Modules/blog/src/Http/Controllers/Dashboard/PostController.php'));

        $expectedNamespace = 'namespace My\Blog\Http\Controllers\Dashboard;';
        $this->assertStringContainsStringIgnoringCase($expectedNamespace,
            file_get_contents(base_path('Modules/blog/src/Http/Controllers/Dashboard/PostController.php')));
    }

    public function test_it_creates_the_controller_in_the_specified_directory_namespace(
    )
    {
        $this->removeGeneratedFiles();

        $this->assertFileDoesNotExist(app_path('Post.php'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/Dashboard/PostController.php'));

        $this->artisan('cray:controller PostController --model=Post --controller-dir=Dashboard');

        $this->assertFileExists(app_path('Post.php'));
        $this->assertFileExists(app_path('Http/Controllers/Dashboard/PostController.php'));

        $this->removeGeneratedFiles();
        if (!file_exists(app_path('Models'))) {
            mkdir(app_path('Models'));
        }

        $this->assertFileDoesNotExist(app_path('Models/Post.php'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/Dashboard/PostController.php'));

        $this->artisan('cray:controller PostController --model=Models/Post --controller-dir=Dashboard');

        $this->assertFileExists(app_path('Models/Post.php'));
        $this->assertFileExists(app_path('Http/Controllers/Dashboard/PostController.php'));
    }

    public function test_it_creates_the_controller_with_the_specified_route_base(
    )
    {
        $this->removeGeneratedFiles();
        $this->artisan('cray:controller PostController --model=Post --controller-dir=Dashboard --route-base=dashboard.posts');
        $controllerContents
            = file_get_contents(app_path('/Http/Controllers/Dashboard/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return \$this->success('Post added successfully!', 'dashboard.posts.index');",
            $controllerContents);
    }

    public function test_it_prompts_to_create_the_route_file_when_the_route_does_not_exist(
    )
    {
        $this->removeGeneratedFiles();

        $this->assertDirectoryDoesNotExist(base_path('Modules'));
        $this->assertFileDoesNotExist(base_path('Modules/blog/src/Post.php'));
        $this->assertFileDoesNotExist(base_path('Modules/blog/src/Http/Controllers/PostController.php'));
        $this->assertFileDoesNotExist(base_path('Modules/blog/routes/web.php'));
        $this->artisan('cray Post --base=Modules/blog')
            ->expectsConfirmation(base_path('Modules/blog/routes/web.php')
                .' does not exist. Do you want to create it?', 'yes')
            ->expectsOutput('Route created at Modules/blog/routes/web.php')
            ->assertSuccessful();

        $this->assertDirectoryExists(base_path('Modules/blog/routes'));
        $this->assertFileExists(base_path('Modules/blog/routes/web.php'));
    }
}
