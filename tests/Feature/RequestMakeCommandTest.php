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

    public function test_it_generates_update_form_request_with_no_options()
    {
        $this->removeGeneratedFiles();
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

        $assertString1 = '$id = $this->route()->parameter(\'post\')->id;';
        $actual = file_get_contents(app_path('Http/Requests/PostUpdateRequest.php'));
        $assertString2 = ' \'id\' => \'required|unique:posts,id,\' . $id,';

        $this->assertStringContainsString($assertString1, $actual);
        $this->assertStringContainsString($assertString2, $actual);
    }

    public function test_it_generates_update_form_request_with_model_in_subfolder()
    {
        //Make sure no artifact related to Post exists
        $this->assertFileDoesNotExist(app_path('Models/Post.php'));
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

    public function test_it_creates_form_request_using_cray_request_command()
    {
        $this->removeGeneratedFiles();
        $this->assertFileDoesNotExist(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->artisan('cray:request PostStoreRequest --model=Post --type=Store');
        $this->assertFileExists(app_path('Http/Requests/PostStoreRequest.php'));

        $this->assertFileDoesNotExist(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->artisan('cray:request PostUpdateRequest --model=Post --type=Update');
        $this->assertFileExists(app_path('Http/Requests/PostUpdateRequest.php'));
    }

    public function test_it_creates_form_request_in_custom_namespace_using_cray_request_command()
    {
        $base = base_path('Modules/blog/');
        $this->removeGeneratedFiles();

        $this->assertFileDoesNotExist($base.'src/Post.php');
        $this->assertFileDoesNotExist($base.'src/Http/Requests/PostStoreRequest.php');

        $this->artisan('cray:request PostStoreRequest --model=Post --type=Store --base=Modules/blog --namespace=Blog/');

        $this->assertFileDoesNotExist($base.'src/Http/Requests/PostUpdateRequest.php');
        $this->assertFileExists($base.'src/Http/Requests/PostStoreRequest.php');

        $base = base_path('Modules/blog/');
        $this->removeGeneratedFiles();

        $this->assertFileDoesNotExist($base.'src/Post.php');
        $this->assertFileDoesNotExist($base.'src/Http/Requests/PostStoreRequest.php');

        $this->artisan('cray:request PostUpdateRequest --model=Post --type=Update --base=Modules/blog --namespace=Blog/');

        $this->assertFileDoesNotExist($base.'src/Http/Requests/PostStoreRequest.php');
        $this->assertFileExists($base.'src/Http/Requests/PostUpdateRequest.php');
    }

    public function test_the_form_request_has_correct_namespace()
    {
        $base = base_path('Modules/blog/');
        $this->artisan('cray:request PostStoreRequest --model=Post --type=Store --base=Modules/blog --namespace=Blog/');

        $expectedNamespace = 'namespace Blog\Http\Requests;';
        $requestClassContents = file_get_contents($base.'src/Http/Requests/PostStoreRequest.php');
        $this->assertStringContainsStringIgnoringCase($expectedNamespace, $requestClassContents);
    }
}
