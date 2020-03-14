<?php

namespace JunaidQadirB\Cray\Tests\Feature;


use Illuminate\Support\Facades\Artisan;
use JunaidQadirB\Cray\Tests\TestCase;

class ModelMakeCommandTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->removeGeneratedFiles();
    }

    public function test_it_generates_a_model()
    {
        //Make sure no artifact related to Post exists
        $this->assertFalse(file_exists(app_path('/Post.php')));


        $this->artisan('cray:model Post');

        $this->assertTrue(file_exists(app_path('/Post.php')));

        $actualOutput = Artisan::output();

        $expectedOutput = "Model created successfully in /app/Post.php" . PHP_EOL;
        $this->assertSame($expectedOutput, $actualOutput);
    }
}
