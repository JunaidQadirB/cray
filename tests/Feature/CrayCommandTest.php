<?php

namespace JunaidQadirB\Cray\Tests\Feature;


use Illuminate\Support\Facades\Artisan;
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

    public function test_it_scaffolds_crud_artifacts()
    {
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
        $this->assertFileExists(resource_path('views/posts'));
        $this->assertFileExists(resource_path("views/posts/index.blade.php"));
        $this->assertFileExists(resource_path("views/posts/create.blade.php"));
        $this->assertFileExists(resource_path("views/posts/_form.blade.php"));
        $this->assertFileExists(resource_path("views/posts/edit.blade.php"));
        $this->assertFileExists(resource_path("views/posts/show.blade.php"));
        $this->assertFileExists(resource_path("views/posts/modals/delete.blade.php"));

        $actualOutput = Artisan::output();

        $expectedOutput = "Factory created successfully in /database/factories/PostFactory.php
Created Migration: 2020_03_14_153546_create_posts_table
Model created successfully in /app/Post.php
Controller created successfully in /app/Http/Controllers/PostController.php
View created successfully in /resources/views/posts/index.blade.php
View created successfully in /resources/views/posts/create.blade.php
View created successfully in /resources/views/posts/_form.blade.php
View created successfully in /resources/views/posts/edit.blade.php
View created successfully in /resources/views/posts/show.blade.php
View created successfully in /resources/views/posts/modals/delete.blade.php
Request created successfully in /app/Http/Requests/PostStoreRequest.php
Request created successfully in /app/Http/Requests/PostUpdateRequest.php" . PHP_EOL;
        $this->assertSame($expectedOutput, $actualOutput);
    }

    public function test_it_scaffolds_crud_artifacts_model_in_models_dir_with_namespace()
    {
        //Make sure no artifact related to Post exists
        $this->assertFileDoesNotExist(app_path('Models/Post.php'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/PostController.php'));
        $this->assertFileDoesNotExist(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->assertFileDoesNotExist(app_path('Http/Requests/PostStoreRequest.php'));
        $this->assertFileDoesNotExist(base_path('database/factories/PostFactory.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts'));

        $this->artisan('cray Models/Post');

        $this->assertFileExists(app_path('Models/Post.php'));
        $this->assertFileExists(app_path('Http/Controllers/PostController.php'));
        $this->assertFileExists(app_path('Http/Requests/PostUpdateRequest.php'));
        $this->assertFileExists(app_path('Http/Requests/PostStoreRequest.php'));
        $this->assertFileExists(base_path('database/factories/PostFactory.php'));
        $this->assertFileExists(resource_path('views/posts'));
        $this->assertFileExists(resource_path("views/posts/index.blade.php"));
        $this->assertFileExists(resource_path("views/posts/create.blade.php"));
        $this->assertFileExists(resource_path("views/posts/_form.blade.php"));
        $this->assertFileExists(resource_path("views/posts/edit.blade.php"));
        $this->assertFileExists(resource_path("views/posts/show.blade.php"));
        $this->assertFileExists(resource_path("views/posts/modals/delete.blade.php"));

        $actualOutput = Artisan::output();

        $expectedOutput = "Factory created successfully in /database/factories/PostFactory.php
Created Migration: 2020_03_14_153546_create_posts_table
Model created successfully in /app/Models/Post.php
Controller created successfully in /app/Http/Controllers/PostController.php
View created successfully in /resources/views/posts/index.blade.php
View created successfully in /resources/views/posts/create.blade.php
View created successfully in /resources/views/posts/_form.blade.php
View created successfully in /resources/views/posts/edit.blade.php
View created successfully in /resources/views/posts/show.blade.php
View created successfully in /resources/views/posts/modals/delete.blade.php
Request created successfully in /app/Http/Requests/PostStoreRequest.php
Request created successfully in /app/Http/Requests/PostUpdateRequest.php" . PHP_EOL;
        $this->assertSame($expectedOutput, $actualOutput);
    }
}
