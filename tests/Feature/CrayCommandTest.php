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
        $this->assertDirectoryNotExists(resource_path('stubs'));
        $this->artisan('vendor:publish --tag=cray');
        $this->assertDirectoryExists(resource_path('stubs'));
    }

    public function test_it_scaffolds_crud_artifacts()
    {
        //Make sure no artifact related to Post exists
        $this->assertFalse(file_exists(app_path('/Post.php')));
        $this->assertFalse(file_exists(app_path('/Http/Controllers/PostController.php')));
        $this->assertFalse(file_exists(app_path('/Http/Requests/PostUpdateRequest.php')));
        $this->assertFalse(file_exists(app_path('/Http/Requests/PostStoreRequest.php')));
        $this->assertFalse(file_exists(base_path('database/factories/PostFactory.php')));
        $this->assertFalse(file_exists(resource_path('views/posts')));

        $this->artisan('cray Post');

        $this->assertTrue(file_exists(app_path('/Post.php')));
        $this->assertTrue(file_exists(app_path('/Http/Controllers/PostController.php')));
        $this->assertTrue(file_exists(app_path('/Http/Requests/PostUpdateRequest.php')));
        $this->assertTrue(file_exists(app_path('/Http/Requests/PostStoreRequest.php')));
        $this->assertTrue(file_exists(base_path('database/factories/PostFactory.php')));
        $this->assertTrue(file_exists(resource_path('views/posts')));
        $this->assertTrue(file_exists(resource_path("views/posts/index.blade.php")));
        $this->assertTrue(file_exists(resource_path("views/posts/create.blade.php")));
        $this->assertTrue(file_exists(resource_path("views/posts/_form.blade.php")));
        $this->assertTrue(file_exists(resource_path("views/posts/edit.blade.php")));
        $this->assertTrue(file_exists(resource_path("views/posts/show.blade.php")));
        $this->assertTrue(file_exists(resource_path("views/posts/modals/delete.blade.php")));

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
}
