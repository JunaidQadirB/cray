<?php

namespace JunaidQadirB\Cray\Tests;

use JunaidQadirB\Cray\CrayServiceProvider;

class ExampleTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [CrayServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
