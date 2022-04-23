<?php

namespace JunaidQadirB\Cray\Tests\Feature;

use JunaidQadirB\Cray\Tests\TestCase;

class CrayCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->removeGeneratedFiles();
    }

    public function test_it_publishes_stubs()
    {
        $this->deleteStubs();
        $this->assertDirectoryDoesNotExist(resource_path('stubs'));
        $this->artisan('vendor:publish --tag=cray');
        $this->assertDirectoryExists(resource_path('stubs'));
    }

    public function test_it_scaffolds_crud_artifacts_with_no_models_directory()
    {
        $this->removeGeneratedFiles();
        //Make sure no artifact related to Post exists
        $this->assertFileDoesNotExist(app_path('Post.php'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/PostController.php'));
        $this->assertFileDoesNotExist(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->assertFileDoesNotExist(app_path('Http/Requests/PostStoreRequest.php'));
        $this->assertFileDoesNotExist(base_path('database/factories/PostFactory.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts'));

        $this->artisan('cray Post');

        $this->assertFileExists(app_path('Post.php'));
        $this->assertFileExists(app_path('Http/Controllers/PostController.php'));
        $this->assertFileExists(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->assertFileExists(app_path('Http/Requests/PostStoreRequest.php'));
        $this->assertFileExists(base_path('database/factories/PostFactory.php'));
        $this->assertDirectoryExists(resource_path('views/posts'));
        $this->assertFileExists(resource_path('views/posts/index.blade.php'));
        $this->assertFileExists(resource_path('views/posts/create.blade.php'));
        $this->assertFileExists(resource_path('views/posts/_form.blade.php'));
        $this->assertFileExists(resource_path('views/posts/edit.blade.php'));
        $this->assertFileExists(resource_path('views/posts/show.blade.php'));
        $this->assertFileExists(resource_path('views/posts/modals/delete.blade.php'));
    }

    public function test_it_scaffolds_crud_artifacts_model_in_models_dir_with_namespace()
    {
        //Make sure no artifact related to Post exists
        if (!file_exists(app_path('Models'))) {
            mkdir(app_path('Models'));
        }

        $this->assertFileDoesNotExist(app_path('Models/Post.php'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/PostController.php'));
        $this->assertFileDoesNotExist(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->assertFileDoesNotExist(app_path('Http/Requests/PostStoreRequest.php'));
        $this->assertFileDoesNotExist(base_path('database/factories/PostFactory.php'));
        $this->assertDirectoryDoesNotExist(resource_path('views/posts'));
        $this->assertFileDoesNotExist(resource_path('views/posts/index.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/create.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/_form.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/edit.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/show.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/modals/delete.blade.php'));

        $this->artisan('cray Models/Post --namespace=Blog/');

        $this->assertFileExists(app_path('Models/Post.php'));
        $this->assertFileExists(app_path('Http/Controllers/PostController.php'));
        $this->assertFileExists(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->assertFileExists(app_path('Http/Requests/PostStoreRequest.php'));
        $this->assertFileExists(base_path('database/factories/PostFactory.php'));
        $this->assertDirectoryExists(resource_path('views/posts'));
        $this->assertFileExists(resource_path('views/posts/index.blade.php'));
        $this->assertFileExists(resource_path('views/posts/create.blade.php'));
        $this->assertFileExists(resource_path('views/posts/_form.blade.php'));
        $this->assertFileExists(resource_path('views/posts/edit.blade.php'));
        $this->assertFileExists(resource_path('views/posts/show.blade.php'));
        $this->assertFileExists(resource_path('views/posts/modals/delete.blade.php'));
    }

    public function test_it_generates_views_and_the_controller_under_the_given_directory_when_controller_directory_is_specified(
    )
    {
        $this->removeGeneratedFiles();

        //Make sure no artifact related to Post exists
        if (!file_exists(app_path('Models'))) {
            mkdir(app_path('Models'));
        }
        $this->assertFileDoesNotExist(app_path('Models/Post.php'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/Dashboard/PostController.php'));
        $this->assertFileDoesNotExist(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->assertFileDoesNotExist(app_path('Http/Requests/PostStoreRequest.php'));
        $this->assertFileDoesNotExist(base_path('database/factories/PostFactory.php'));
        $this->assertDirectoryDoesNotExist(resource_path('views/dashboard/posts'));
        $this->assertFileDoesNotExist(resource_path('views/dashboard/posts/index.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/dashboard/posts/create.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/dashboard/posts/_form.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/dashboard/posts/edit.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/dashboard/posts/show.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/dashboard/posts/modals/delete.blade.php'));

        $this->artisan('cray Models/Post --controller-dir=dashboard --views-dir=dashboard');

        $this->assertFileExists(app_path('Models/Post.php'));
        $this->assertFileExists(app_path('Http/Controllers/Dashboard/PostController.php'));
        $this->assertFileExists(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->assertFileExists(app_path('Http/Requests/PostStoreRequest.php'));
        $this->assertFileExists(base_path('database/factories/PostFactory.php'));
        $this->assertDirectoryExists(resource_path('views/dashboard/posts'));
        $this->assertFileExists(resource_path('views/dashboard/posts/index.blade.php'));
        $this->assertFileExists(resource_path('views/dashboard/posts/create.blade.php'));
        $this->assertFileExists(resource_path('views/dashboard/posts/_form.blade.php'));
        $this->assertFileExists(resource_path('views/dashboard/posts/edit.blade.php'));
        $this->assertFileExists(resource_path('views/dashboard/posts/show.blade.php'));
        $this->assertFileExists(resource_path('views/dashboard/posts/modals/delete.blade.php'));
    }

    public function test_it_generates_view_paths_correctly_when_subdirectory_is_specified_for_the_controller()
    {
        $this->artisan('cray Models/Post --controller-dir=dashboard --views-dir=dashboard/system');
        $createBladeView = file_get_contents(resource_path('views/dashboard/system/posts/create.blade.php'));
        $postController = file_get_contents(app_path('Http/Controllers/Dashboard/PostController.php'));

        $this->assertStringContainsString("@include('dashboard.system.posts._form')", $createBladeView,
            'Include path is incorrect');

        $this->assertStringContainsString("return view('dashboard.system.posts.index'", $postController,
            'View path is incorrect');
    }

    public function test_it_generates_view_paths_correctly()
    {
        $this->artisan('cray Models/Post');
        $createBladeView = file_get_contents(resource_path('views/posts/create.blade.php'));
        $postController = file_get_contents(app_path('Http/Controllers/PostController.php'));

        $this->assertStringContainsString("@include('posts._form')", $createBladeView, 'Include path is incorrect');

        $this->assertStringContainsString("return view('posts.index'", $postController, 'View path is incorrect');
    }

    public function test_it_generates_route_names_correctly()
    {
        $this->removeGeneratedFiles();

        $this->artisan('cray Models/Post --route-base=custom-route');
        $createBladeView = file_get_contents(resource_path('views/posts/create.blade.php'));
        $postController = file_get_contents(app_path('Http/Controllers/PostController.php'));

        $this->assertStringContainsString("route('custom-route.index')", $createBladeView, 'Include path is incorrect');
        $this->assertStringContainsString('return $this->success(\'Post added successfully!\', \'custom-route.index\');',
            $postController, 'View path is incorrect');
    }

    public function test_it_adds_route_for_the_controller()
    {
        if (!file_exists(base_path('routes/web.php'))) {
            touch(base_path('routes/web.php'));
            file_put_contents(base_path('routes/web.php'), "<?php\n\n");
        }
        $this->artisan('cray Models/Post --route-base=custom-route');

        $this->assertStringContainsString("Route::resource('custom-route', App\\Http\\Controllers\PostController::class)",
            file_get_contents(base_path('routes/web.php')), 'Route not added');
    }

    public function test_it_generates_update_form_request_with_custom_base()
    {
        //Make sure no artifact related to Post exists

        $base = base_path('Modules/blog/');
        $this->removeGeneratedFiles();
        $this->assertFileDoesNotExist($base.'src/Post.php');
        $this->assertFileDoesNotExist($base.'src/Http/Controllers/PostController.php');
        $this->assertFileDoesNotExist($base.'src/Http/Requests/PostUpdateRequest.php');
        $this->assertFileDoesNotExist($base.'src/Http/Requests/PostStoreRequest.php');
        $this->assertFileDoesNotExist($base.'database/factories/PostFactory.php');
        $this->assertDirectoryDoesNotExist($base.'resources/views/posts');

        $this->artisan('cray Post --namespace=Blog/ --base=Modules/blog');

        $this->assertFileExists($base.'src/Post.php');
        $this->assertFileExists($base.'src/Http/Controllers/PostController.php');
        $this->assertFileExists($base.'src/Http/Requests/PostUpdateRequest.php');
        $this->assertFileExists($base.'src/Http/Requests/PostStoreRequest.php');
        $this->assertFileExists($base.'database/factories/PostFactory.php');
        $this->assertDirectoryExists($base.'resources/views/posts');
        /*        $assertString1 = '$id = $this->route()->parameter(\'post\')->id;';
                $actual = file_get_contents($base.'src/Http/Requests/PostUpdateRequest.php');
                $assertString2 = ' \'id\' => \'required|unique:posts,id,\' . $id,';

                $this->assertStringContainsString($assertString1, $actual);
                $this->assertStringContainsString($assertString2, $actual);*/
    }

    public function test_it_scaffolds_crud_artifacts_with_namespaces_form_requests()
    {
        $this->removeGeneratedFiles();

        $this->assertFileDoesNotExist(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->assertFileDoesNotExist(app_path('Http/Requests/PostStoreRequest.php'));

        $this->artisan('cray Models/Post --namespace=Blog/');

        $this->assertFileExists(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->assertFileExists(app_path('Http/Requests/PostStoreRequest.php'));

        $expectedNamespace = 'namespace Blog\Http\Requests;';

        $requestClassContents = file_get_contents(app_path('Http/Requests/PostStoreRequest.php'));
        $this->assertStringContainsStringIgnoringCase($expectedNamespace, $requestClassContents);

        /**
         * With base.
         */
        $base = base_path('Modules/blog');
        $this->assertFileDoesNotExist($base.'/src/Http/Requests/PostUpdateRequest.php');
        $this->assertFileDoesNotExist($base.'/src/Http/Requests/PostStoreRequest.php');

        $this->artisan('cray Models/Post --namespace=Blog/ --base=Modules/blog');

        $this->assertFileExists($base.'/src/Http/Requests/PostUpdateRequest.php');
        $this->assertFileExists($base.'/src/Http/Requests/PostStoreRequest.php');

        $expectedNamespace2 = 'namespace Blog\Http\Requests;';

        $requestClassContents2 = file_get_contents($base.'/src/Http/Requests/PostStoreRequest.php');
        $this->assertStringContainsStringIgnoringCase($expectedNamespace2, $requestClassContents2);
    }

    public function test_it_should_have_no_reference_to_cray_in_generated_files()
    {
        $this->removeGeneratedFiles();

        $this->removeGeneratedFiles();
        $this->assertFileDoesNotExist('Post.php');
        $this->assertFileDoesNotExist('app/Http/Controllers/PostController.php');
        $this->assertFileDoesNotExist('app/Http/Requests/PostUpdateRequest.php');
        $this->assertFileDoesNotExist('app/Http/Requests/PostStoreRequest.php');
        $this->assertFileDoesNotExist('database/factories/PostFactory.php');
        $this->assertDirectoryDoesNotExist(resource_path('views/posts'));

        $this->artisan('cray Post');

        $needle = 'cray';
        $haystack = app_path('Post.php');
        $this->assertStringNotContainsStringIgnoringCase($needle, file_get_contents($haystack));

        $haystack = app_path('Http/Controllers/PostController.php');
        $this->assertStringNotContainsStringIgnoringCase($needle, file_get_contents($haystack));

        $haystack = app_path('Http/Requests/PostUpdateRequest.php');
        $this->assertStringNotContainsStringIgnoringCase($needle, file_get_contents($haystack));

        $haystack = app_path('Http/Requests/PostStoreRequest.php');
        $this->assertStringNotContainsStringIgnoringCase($needle, file_get_contents($haystack));

        $haystack = database_path('factories/PostFactory.php');
        $this->assertStringNotContainsStringIgnoringCase($needle, file_get_contents($haystack));

        $haystack = resource_path('views/posts/index.blade.php');
        $this->assertStringNotContainsStringIgnoringCase($needle, file_get_contents($haystack));

        $haystack = resource_path('views/posts/create.blade.php');
        $this->assertStringNotContainsStringIgnoringCase($needle, file_get_contents($haystack));

        $haystack = resource_path('views/posts/edit.blade.php');
        $this->assertStringNotContainsStringIgnoringCase($needle, file_get_contents($haystack));

        $haystack = resource_path('views/posts/show.blade.php');
        $this->assertStringNotContainsStringIgnoringCase($needle, file_get_contents($haystack));
    }

    public function test_it_does_not_create_views_when_no_views_option_is_passed()
    {
        $this->removeGeneratedFiles();
        $this->assertFileDoesNotExist('Post.php');
        $this->assertFileDoesNotExist('app/Http/Controllers/PostController.php');
        $this->assertFileDoesNotExist('app/Http/Requests/PostUpdateRequest.php');
        $this->assertFileDoesNotExist('app/Http/Requests/PostStoreRequest.php');
        $this->assertFileDoesNotExist('database/factories/PostFactory.php');
        $this->assertDirectoryDoesNotExist(resource_path('views/posts'));

        $this->artisan('cray Post --no-views');
        $this->assertDirectoryDoesNotExist(resource_path('views/posts'));
    }
}
