<?php


namespace ElegantTechnologies\Grok\Tests\Feature\Http\Controllers;

class GrokControllerTest extends \ElegantTechnologies\Grok\Tests\TestCase
{
    /** @test */
    public function global_urls_returns_ok()
    {
        // Test hard-coded routes...
        $this
            ->get('/grok/ElegantTechnologies/Grok/sample_string')
            ->assertOk()
            ->assertSee('Hello string via global url.');
        $this
            ->get('/grok/ElegantTechnologies/Grok/sample_blade')
            ->assertOk()
            ->assertSee('Hi from blade in ElegantTechnologies/Grok/groks/sample');
        $this
            ->get('/grok/ElegantTechnologies/Grok/sample_controller')
            ->assertOk()
            ->assertSee('Hello from: ElegantTechnologies\Grok\Http\Controllers\GrokController::sample');
    }


    /** @test */
    public function prefixed_urls_returns_ok()
    {
        // Test user-defined routes...
        // Reproduce in routes/web.php like so
        //  Route::grok('staff');
        //  http://test-eleganttechnologies.test/staff/ElegantTechnologies/Grok/string
        //  http://test-eleganttechnologies.test/staff/ElegantTechnologies/Grok/blade
        //  http://test-eleganttechnologies.test/staff/ElegantTechnologies/Grok/controller
        $userDefinedBladePrefix = $this->userDefinedBladePrefix; // user will do this in routes/web.php Route::grok('url');

        // string
        $this
            ->get("/$userDefinedBladePrefix/ElegantTechnologies/Grok/sample_string")
            ->assertOk()
            #->assertSee('hw(ElegantTechnologies\Grok\Http\Controllers\GrokController)');
        ->assertSee('Hello string via blade prefix');

        // blade
//        $this
//            ->get("/$userDefinedBladePrefix/ElegantTechnologies/Grok/sample_blade")
//            ->assertOk()
//            ->assertSee('Hi from blade in ElegantTechnologies/Grok/groks/sample');

//        // controller
//        $this
//            ->get("/$userDefinedBladePrefix/ElegantTechnologies/Grok/sample_controller")
//            ->assertOk()
//            ->assertSee('Hello from: ElegantTechnologies\Grok\Http\Controllers\GrokController::sample');
    }
}
