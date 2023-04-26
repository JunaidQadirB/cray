<?php

namespace JunaidQadirB\Cray\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use JunaidQadirB\Cray\Tests\TestCase;

class ModelMakeCommandTest extends TestCase
{
    public function test_it_generates_a_model()
    {
        $this->removeGeneratedFiles();

        //Make sure no artifact related to Post exists
        $this->assertFalse(file_exists(app_path('/Post.php')));

        $this->artisan('cray:model Post');

        $this->assertTrue(file_exists(app_path('/Post.php')));

        $actualOutput = Artisan::output();

        $expectedOutput = 'Model created successfully in /app/Post.php' . PHP_EOL;
        $this->assertSame($expectedOutput, $actualOutput);
    }

    public function test_it_generates_a_model_in_the_given_directory_and_namespace()
    {
        $this->removeGeneratedFiles();

        $this->assertFileDoesNotExist(app_path('Models/Post.php'));

        $this->artisan('cray:model Models/Post --namespace=Modules/');

        $this->assertFileExists(app_path('Models/Post.php'));

        $actualOutput = Artisan::output();

        $expectedOutput = 'Model created successfully in /app/Models/Post.php' . PHP_EOL;
        $this->assertSame($expectedOutput, $actualOutput);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->removeGeneratedFiles();
    }
}
