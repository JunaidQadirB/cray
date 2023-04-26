<?php

namespace JunaidQadirB\Cray\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use JunaidQadirB\Cray\Tests\TestCase;

class FactoryMakeCommandTest extends TestCase
{
    public function test_it_generates_a_factory_in_default_path()
    {
        //Make sure no artifact related to Post exists
        $this->assertFalse(file_exists(base_path('database/factories/PostFactory.php')));

        $this->artisan('cray:factory PostFactory');

        $this->assertTrue(file_exists(base_path('database/factories/PostFactory.php')));

        $actualOutput = Artisan::output();

        $expectedOutput = 'Factory created successfully in /database/factories/PostFactory.php' . PHP_EOL;
        $this->assertSame($expectedOutput, $actualOutput);
    }

    public function test_it_generates_a_factory_in_custom_path()
    {
        //Make sure no artifact related to Post exists
        $this->assertFileDoesNotExist(base_path('Modules/blog/src/database/factories/PostFactory.php'));

        $this->artisan('cray:factory PostFactory --base=Modules/blog/src');

        $this->assertFileExists(base_path('Modules/blog/src/database/factories/PostFactory.php'));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->removeGeneratedFiles();
    }
}
