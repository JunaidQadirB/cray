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
        $this->artisan('cray:controller PostController');
        $output = Artisan::output();
        $expectedOutput = 'Controller created successfully in /app/Http/Controllers/PostController.php' . PHP_EOL;
        $this->assertSame($expectedOutput, $output);
    }

    public function test_it_gives_an_error_if_controller_exists()
    {
        $this->artisan('cray:controller PostController');
        $this->artisan('cray:controller PostController');
        $output = Artisan::output();
        $this->assertSame('Controller already exists!' . PHP_EOL, $output);
    }

    public function test_generates_a_resource_controller_for_the_given_model()
    {
        $this->artisan('cray:controller PostController --model=Post');
        $output = Artisan::output();
        $expected = 'Model created successfully in /media/junaidqadir/Personal/code/cray/vendor/orchestra/testbench-core/laravel/app/Post.php' . PHP_EOL
            . 'Controller created successfully in /app/Http/Controllers/PostController.php' . PHP_EOL;
        $this->assertSame($expected, $output);
        $this->assertFileExists(app_path('/Http/Controllers/PostController.php'));
    }

    public function test_it_uses_views_path_specified_in_views_dir_option()
    {
        //Scenario 1
        $this->artisan('cray:controller PostController --model=Post --views-dir=posts');
        $controllerContents = file_get_contents(app_path('/Http/Controllers/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return view('posts.index', compact('posts'));", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'posts.edit'", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'posts.show'", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'posts.create'", $controllerContents);

        unlink(app_path('Http/Controllers/PostController.php'));

        //Scenario 2
        $this->artisan('cray:controller PostController --model=Post --views-dir=blog_posts');
        $controllerContents = file_get_contents(app_path('/Http/Controllers/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return view('blog_posts.index', compact('posts'));", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog_posts.edit'", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog_posts.show'", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog_posts.create'", $controllerContents);

        unlink(app_path('Http/Controllers/PostController.php'));

        //Scenario 3
        $this->artisan('cray:controller PostController --model=Post --views-dir=blog/posts');
        $controllerContents = file_get_contents(app_path('/Http/Controllers/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return view('blog.posts.index', compact('posts'));", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog.posts.edit'", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog.posts.show'", $controllerContents);
        $this->assertStringContainsStringIgnoringCase("'blog.posts.create'", $controllerContents);
    }

    public function test_it_uses_the_specified_route_or_falls_back_to_model_slug()
    {
        //Scenario 1
        $this->artisan('cray:controller PostController --model=Post --views-dir=posts --route-base=my-posts');
        $controllerContents = file_get_contents(app_path('/Http/Controllers/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return \$this->success('Post added successfully!', 'my-posts.index');", $controllerContents);

        unlink(app_path('Http/Controllers/PostController.php'));

        //Scenario 1
        $this->artisan('cray:controller PostController --model=Post --views-dir=posts');
        $controllerContents = file_get_contents(app_path('/Http/Controllers/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return \$this->success('Post added successfully!', 'posts.index');", $controllerContents);
    }

    public function test_it_creates_the_controller_in_the_specified_directory_namespace()
    {
        $this->removeGeneratedFiles();
        $this->artisan('cray:controller PostController --model=Post --controller-dir=Dashboard');
        $output = Artisan::output();
        $expected = "Model created successfully in /media/junaidqadir/Personal/code/cray/vendor/orchestra/testbench-core/laravel/app/Post.php" . PHP_EOL
            . "Controller created successfully in /app/Http/Controllers/Dashboard/PostController.php" . PHP_EOL;
        $this->assertSame($expected, $output);
    }

    public function test_it_creates_the_controller_with_the_specified_route_base()
    {
        $this->removeGeneratedFiles();
        $this->artisan('cray:controller PostController --model=Post --controller-dir=Dashboard --route-base=dashboard.posts');
        $controllerContents = file_get_contents(app_path('/Http/Controllers/Dashboard/PostController.php'));
        $this->assertStringContainsStringIgnoringCase("return \$this->success('Post added successfully!', 'dashboard.posts.index');", $controllerContents);
    }

}
