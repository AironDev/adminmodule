<?php

namespace Modules\Admin\Tests\Unit;

use Illuminate\Support\Facades\Config;
use Modules\Admin\Facades\AdminModule;
use Modules\Admin\Tests\TestCase;

class AdminModuleTest extends TestCase
{
    /**
     * Dimmers returns an array filled with widget collections.
     *
     * This test will make sure that the dimmers method will give us an array
     * of the collection of the configured widgets.
     */
    public function testDimmersReturnsCollectionOfConfiguredWidgets()
    {
        Config::set('voyager.dashboard.widgets', [
            'Modules\\Admin\\Tests\\Stubs\\Widgets\\AccessibleDimmer',
            'Modules\\Admin\\Tests\\Stubs\\Widgets\\AccessibleDimmer',
        ]);

        $dimmers = AdminModule::dimmers();

        $this->assertEquals(2, $dimmers[0]->count());
    }

    /**
     * Dimmers returns an array filled with widget collections.
     *
     * This test will make sure that the dimmers method will give us a
     * collection of the configured widgets which also should be displayed.
     */
    public function testDimmersReturnsCollectionOfConfiguredWidgetsWhichShouldBeDisplayed()
    {
        Config::set('voyager.dashboard.widgets', [
            'Modules\\Admin\\Tests\\Stubs\\Widgets\\AccessibleDimmer',
            'Modules\\Admin\\Tests\\Stubs\\Widgets\\InAccessibleDimmer',
            'Modules\\Admin\\Tests\\Stubs\\Widgets\\InAccessibleDimmer',
        ]);

        $dimmers = AdminModule::dimmers();

        $this->assertEquals(1, $dimmers[0]->count());
    }

    /**
     * Dimmers returns an array filled with widget collections.
     *
     * Tests that we build N / 3 (rounded up) widget collections where
     * N is the total amount of widgets set in configuration
     */
    public function testCreateEnoughDimmerCollectionsToContainAllAvailableDimmers()
    {
        Config::set('voyager.dashboard.widgets', [
            'Modules\\Admin\\Tests\\Stubs\\Widgets\\AccessibleDimmer',
            'Modules\\Admin\\Tests\\Stubs\\Widgets\\AccessibleDimmer',
            'Modules\\Admin\\Tests\\Stubs\\Widgets\\AccessibleDimmer',
            'Modules\\Admin\\Tests\\Stubs\\Widgets\\AccessibleDimmer',
            'Modules\\Admin\\Tests\\Stubs\\Widgets\\AccessibleDimmer',
        ]);

        $dimmers = AdminModule::dimmers();

        $this->assertEquals(2, count($dimmers));
        $this->assertEquals(3, $dimmers[0]->count());
        $this->assertEquals(2, $dimmers[1]->count());
    }
}
