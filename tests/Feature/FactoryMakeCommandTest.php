<?php

namespace JunaidQadirB\Cray\Tests\Feature;


use Illuminate\Support\Facades\Artisan;
use JunaidQadirB\Cray\Tests\TestCase;

class FactoryMakeCommandTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->removeGeneratedFiles();
    }

    public function test_it_generates_a_factory()
    {
        //Make sure no artifact related to Post exists
        $this->assertFalse(file_exists(base_path('database/factories/PostFactory.php')));


        $this->artisan('cray:factory PostFactory');

        $this->assertTrue(file_exists(base_path('database/factories/PostFactory.php')));

        $actualOutput = Artisan::output();

        $expectedOutput = "Factory created successfully in /database/factories/PostFactory.php" . PHP_EOL;
        $this->assertSame($expectedOutput, $actualOutput);
    }
}
