<?php

namespace JunaidQadirB\Cray\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use JunaidQadirB\Cray\Tests\TestCase;

class ViewMakeCommandTest extends TestCase
{
    public function test_stubs_must_exist()
    {
        $this->assertFileExists(resource_path('/stubs/view/index.stub'));
    }

    public function test_cray_view_command_generates_all_views()
    {
        $this->removeGeneratedFiles();

        $this->withoutMockingConsoleOutput();
        $this->assertDirectoryDoesNotExist(resource_path('views/posts'));

        $this->artisan('cray:view Post');

        $this->assertDirectoryExists(resource_path('views/posts'));
        $this->assertFileExists(resource_path('views/posts/index.blade.php'));
        $this->assertFileExists(resource_path('views/posts/edit.blade.php'));
        $this->assertFileExists(resource_path('views/posts/show.blade.php'));
        $this->assertFileExists(resource_path('views/posts/_form.blade.php'));
        $this->assertFileExists(resource_path('views/posts/modals/delete.blade.php'));
        $this->assertStringContainsString('Posts', file_get_contents(resource_path('views/posts/index.blade.php')));
        $this->assertStringContainsStringIgnoringCase("route('posts.create')", file_get_contents(resource_path('views/posts/index.blade.php')));

        return Artisan::output();
    }

    public function test_cray_view_command_generates_all_views_in_the_specified_directory()
    {
        $this->removeGeneratedFiles();

        $this->withoutMockingConsoleOutput();
        $this->assertDirectoryDoesNotExist(resource_path('views/blog'));

        $this->artisan('cray:view Post --dir=blog');

        $this->assertDirectoryExists(resource_path('views/blog'));
        $this->assertFileExists(resource_path('views/blog/index.blade.php'));
        $this->assertFileExists(resource_path('views/blog/edit.blade.php'));
        $this->assertFileExists(resource_path('views/blog/show.blade.php'));
        $this->assertFileExists(resource_path('views/blog/_form.blade.php'));
        $this->assertFileExists(resource_path('views/blog/modals/delete.blade.php'));
        $this->assertStringContainsString('Posts', file_get_contents(resource_path('views/blog/index.blade.php')));
        $this->assertStringContainsStringIgnoringCase("route('posts.create')", file_get_contents(resource_path('views/blog/index.blade.php')));
    }

    public function test_cray_view_command_generates_all_views_in_the_specified_subdirectory()
    {
        $this->removeGeneratedFiles();

        $this->withoutMockingConsoleOutput();
        $this->assertDirectoryDoesNotExist(resource_path('views/dashboard/blog'));

        $this->artisan('cray:view Post --dir=dashboard/blog');

        $this->assertDirectoryExists(resource_path('views/dashboard/blog'));
        $this->assertFileExists(resource_path('views/dashboard/blog/index.blade.php'));
        $this->assertFileExists(resource_path('views/dashboard/blog/edit.blade.php'));
        $this->assertFileExists(resource_path('views/dashboard/blog/show.blade.php'));
        $this->assertFileExists(resource_path('views/dashboard/blog/_form.blade.php'));
        $this->assertFileExists(resource_path('views/dashboard/blog/modals/delete.blade.php'));
        $this->assertStringContainsString('Posts', file_get_contents(resource_path('views/dashboard/blog/index.blade.php')));
        $this->assertStringContainsStringIgnoringCase("route('posts.create')", file_get_contents(resource_path('views/dashboard/blog/index.blade.php')));
    }

    public function test_cray_view_command_generates_views_in_the_base()
    {
        $this->removeGeneratedFiles();
        $base = base_path('Modules/blog/resources/');

        $this->withoutMockingConsoleOutput();
        $this->assertDirectoryDoesNotExist($base.'views/posts');

        $this->artisan('cray:view Post --base=Modules/blog');

        $this->assertDirectoryExists($base.'views/posts');
        $this->assertFileExists($base.'views/posts/index.blade.php');
        $this->assertFileExists($base.'views/posts/_form.blade.php');
        $this->assertFileExists($base.'views/posts/modals/delete.blade.php');
        $this->assertStringContainsString('Posts', file_get_contents($base.'views/posts/index.blade.php'));
        $this->assertStringContainsString("@include('posts::posts", file_get_contents($base.'views/posts/edit.blade.php'));
    }

    public function test_cray_view_command_generates__all_views_with_model_is_in_a_subdirectory()
    {
        $this->withoutMockingConsoleOutput();
        $this->assertDirectoryDoesNotExist(resource_path('views/posts'));

        $this->artisan('cray:view Models/Post');

        $this->assertDirectoryExists(resource_path('views/posts'));
        $this->assertFileExists(resource_path('views/posts/index.blade.php'));
        $this->assertFileExists(resource_path('views/posts/_form.blade.php'));
        $this->assertFileExists(resource_path('views/posts/modals/delete.blade.php'));
        $this->assertStringContainsString('Posts', file_get_contents(resource_path('views/posts/index.blade.php')));
        $this->assertStringContainsString("route('posts.index')", file_get_contents(resource_path('views/posts/modals/delete.blade.php')));
    }

    public function test_it_will_create_only_index_file()
    {
        $this->removeGeneratedFiles();
        $this->assertDirectoryDoesNotExist(resource_path('views/posts'));
        $this->assertFileDoesNotExist(resource_path('views/posts/index.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/create.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/edit.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/show.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/_form.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/modals/delete.blade.php'));
        $this->artisan('cray:view Post -i');
        $this->assertFileExists(resource_path('views/posts/index.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/show.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/create.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/_form.blade.php'));
        $this->assertFileExists(resource_path('views/posts/modals/delete.blade.php'));
    }

    public function test_it_will_create_only_index_file_with_model_in_a_subdirectory()
    {
        $this->removeGeneratedFiles();
        $this->assertDirectoryDoesNotExist(resource_path('views/blog/posts'));
        $this->assertFileDoesNotExist(resource_path('views/posts/index.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/create.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/edit.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/show.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/_form.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/modals/delete.blade.php'));
        $this->artisan('cray:view Models/Post -i');
        $output = Artisan::output();
        $this->assertFileExists(resource_path('views/posts/index.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/show.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/create.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/_form.blade.php'));
        $this->assertFileExists(resource_path('views/posts/modals/delete.blade.php'));

        $this->assertStringContainsString("route('posts.index')", file_get_contents(resource_path('views/posts/modals/delete.blade.php')));
    }

    public function test_it_will_create_only_create_file()
    {
        $this->assertDirectoryDoesNotExist(resource_path('views/posts'));
        $this->artisan('cray:view Post -c');
        $output = Artisan::output();
        $this->assertFileExists(resource_path('views/posts/create.blade.php'));
        $this->assertFileExists(resource_path('views/posts/_form.blade.php'));
    }

    public function test_it_will_create_only_edit_file()
    {
        $this->assertDirectoryDoesNotExist(resource_path('views/posts'));
        $this->artisan('cray:view Post -e');
        $output = Artisan::output();
        $this->assertFileExists(resource_path('views/posts/edit.blade.php'));
        $this->assertFileExists(resource_path('views/posts/_form.blade.php'));
    }

    public function test_it_will_create_only_show_file()
    {
        $this->assertDirectoryDoesNotExist(resource_path('views/posts'));
        $this->assertFileDoesNotExist(resource_path('views/posts/show.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/create.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/index.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/_form.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/modals/delete.blade.php'));
        $this->artisan('cray:view Post -s');
        $output = Artisan::output();
        $this->assertFileExists(resource_path('views/posts/show.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/index.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/create.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/edit.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/_form.blade.php'));
        $this->assertFileDoesNotExist(resource_path('views/posts/modals/delete.blade.php'));
    }

    public function test_it_will_give_error_message_when_index_view_exists()
    {
        $this->withoutMockingConsoleOutput();
        $this->artisan('cray:view Post -i');
        $this->assertDirectoryExists(resource_path('views/posts'));
        $this->artisan('cray:view Post -i');
        $output = Artisan::output();
        $this->assertSame('File already exists. Cannot overwrite /resources/views/posts/index.blade.php.'.PHP_EOL.
            'File already exists. Cannot overwrite /resources/views/posts/modals/delete.blade.php.'.PHP_EOL,
            $output);
    }

    public function test_it_will_overwrite_existing_view_when_index_view_is_passed_force_flag()
    {
        $this->withoutMockingConsoleOutput();
        $this->artisan('cray:view Post -i');
        $this->assertDirectoryExists(resource_path('views/posts'));
        $this->artisan('cray:view Post -i --force');
        $output = Artisan::output();
        $this->assertSame('View overwritten successfully in /resources/views/posts/index.blade.php'.PHP_EOL.
            'View overwritten successfully in /resources/views/posts/modals/delete.blade.php'.PHP_EOL, $output);
    }

    public function test_it_will_give_error_message_when_create_view_exists()
    {
        $this->withoutMockingConsoleOutput();
        $this->assertDirectoryDoesNotExist(resource_path('views/posts'));
        $this->artisan('cray:view Post -c');
        $this->assertDirectoryExists(resource_path('views/posts'));
        $this->artisan('cray:view Post -c');
        $output = Artisan::output();
        $this->assertSame('File already exists. Cannot overwrite /resources/views/posts/create.blade.php.'.PHP_EOL,
            $output);
    }

    public function test_it_will_overwrite_existing_view_when_create_view_is_passed_force_flag()
    {
        $this->withoutMockingConsoleOutput();
        $this->assertDirectoryDoesNotExist(resource_path('views/posts'));
        $this->artisan('cray:view Post -c');
        $this->assertDirectoryExists(resource_path('views/posts'));
        $this->artisan('cray:view Post -c --force');
        $output = Artisan::output();
        $this->assertSame('View overwritten successfully in /resources/views/posts/create.blade.php'.PHP_EOL.
            'View created successfully in /resources/views/posts/_form.blade.php'.PHP_EOL, $output);
    }
}
