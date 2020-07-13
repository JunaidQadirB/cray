<?php


namespace JunaidQadirB\Cray\Tests\Feature;


use Illuminate\Support\Facades\Artisan;
use JunaidQadirB\Cray\Tests\TestCase;

class RequestMakeCommandTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->removeGeneratedFiles();
    }

    public function test_it_generates_update_form_request()
    {
        //Make sure no artifact related to Post exists
        $this->assertFileDoesNotExist(app_path('Post.php'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/PostController.php'));
        $this->assertFileDoesNotExist(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->assertFileDoesNotExist(app_path('Http/Requests/PostStoreRequest.php'));
        $this->assertFileDoesNotExist(base_path('database/factories/PostFactory.php'));
        $this->assertDirectoryDoesNotExist(resource_path('views/posts'));

        $this->artisan('cray Post');

        $this->assertFileExists(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->assertFileExists(app_path('Http/Requests/PostStoreRequest.php'));

        $actualOutput = Artisan::output();

        $assertString1 = '$id = $this->route()->parameter(\'post\')->id;';
        $actual = file_get_contents(app_path('Http/Requests/PostUpdateRequest.php'));
        $assertString2 = ' \'id\' => \'required|unique:posts,id,\' . $id,';

        $this->assertStringContainsString($assertString1, $actual);
        $this->assertStringContainsString($assertString2, $actual);
    }

    public function test_it_generates_update_form_request_with_model_in_subfolder()
    {
        //Make sure no artifact related to Post exists
        $this->assertFileDoesNotExist(app_path('ModelsPost.php'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/PostController.php'));
        $this->assertFileDoesNotExist(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->assertFileDoesNotExist(app_path('Http/Requests/PostStoreRequest.php'));
        $this->assertFileDoesNotExist(base_path('database/factories/PostFactory.php'));
        $this->assertDirectoryDoesNotExist(resource_path('views/posts'));

        $this->artisan('cray Post');

        $this->assertFileExists(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->assertFileExists(app_path('Http/Requests/PostStoreRequest.php'));

        $actualOutput = Artisan::output();

        $assertString1 = '$id = $this->route()->parameter(\'post\')->id;';
        $actual = file_get_contents(app_path('Http/Requests/PostUpdateRequest.php'));
        $assertString2 = ' \'id\' => \'required|unique:posts,id,\' . $id,';

        $this->assertStringContainsString($assertString1, $actual);
        $this->assertStringContainsString($assertString2, $actual);
    }
}
