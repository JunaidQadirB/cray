<?php

namespace JunaidQadirB\Cray\Tests;

use JunaidQadirB\Cray\CrayServiceProvider;

class ExampleTest extends TestCase
{
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }

    protected function getPackageProviders($app)
    {
        return [CrayServiceProvider::class];
    }
}
