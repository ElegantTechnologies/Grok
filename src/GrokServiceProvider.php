<?php

namespace ElegantTechnologies\Grok;

use ElegantTechnologies\Grok\Commands\GrokCommand;
use ElegantTechnologies\Grok\Http\Controllers\GrokController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class GrokServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../config/grok.php' => config_path('grok.php'),
                ],
                'config'
            );

            $this->publishes(
                [
                    __DIR__ . '/../resources/views' => base_path('resources/views/vendor/grok'),
                ],
                'views'
            );

            $migrationFileName = 'create_grok_table.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes(
                    [
                        __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path(
                            'migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName
                        ),
                    ],
                    'migrations'
                );
            }

            $this->commands(
                [
                    GrokCommand::class,
                ]
            );
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'grok');


        Route::macro(
            'grok',
            function (string $prefix) {
                Route::prefix($prefix)->group(
                    function () {
                        // Sample routes that only show while developing...
                        if (App::environment(['local', 'testing'])) {
                            // prefixed url to string
                            Route::get(
                                '/ElegantTechnologies/Grok/string', // you will absolutely need a prefix in your url
                                function () {
                                    return "Hello string via blade prefix";
                                }
                            );

                            // prefixed url to blade view
                            Route::get(
                                '/ElegantTechnologies/Grok/blade',
                                function () {
                                    return view('grok::grok/index');
                                }
                            );

                            // prefixed url to controller
                            Route::get(
                                '/ElegantTechnologies/Grok/controller',
                                [GrokController::class, 'sample']
                            );
                        }
                    }
                );
            }
        );


        if (App::environment(['local', 'testing'])) {
            // global url to string
            Route::get(
                '/grok/ElegantTechnologies/Grok/string',
                function () {
                    return "Hello string via global url.";
                }
            );

            // global url to blade view
            Route::get(
                '/grok/ElegantTechnologies/Grok/blade',
                function () {
                    return view('grok::grok/index');
                }
            );

            // global url to controller
            Route::get('/grok/ElegantTechnologies/Grok/controller', [GrokController::class, 'sample']);
        }

        /* Configure the routes offered by the application.
           Still learning - this should work, but gives Trying to get property 'profile_photo_url' of non-object (View:
           And for livewire, getting an alert 'this page timed out'
           #$this->loadRoutesFrom(__DIR__.'/routes.php');
        */
        \Livewire::component('grok::a-a-nothing', \ElegantTechnologies\Grok\Components\DemoUiChunks\AANothing::class);
        \Livewire::component('grok::a-b-almost-nothing', \ElegantTechnologies\Grok\Components\DemoUiChunks\ABAlmostNothing::class);
        \Livewire::component('grok::a-c-nothing-but-formatted', \ElegantTechnologies\Grok\Components\DemoUiChunks\ACNothingButFormatted::class);
        \Livewire::component('grok::b-a-button', \ElegantTechnologies\Grok\Components\DemoUiChunks\BAButton::class);
        \Livewire::component('grok::b-b-button-count', \ElegantTechnologies\Grok\Components\DemoUiChunks\BBButtonCount::class);
        \Livewire::component('grok::b-c-button-modal', \ElegantTechnologies\Grok\Components\DemoUiChunks\BCButtonModal::class);
        \Livewire::component('grok::b-d-button-modal-dialog', \ElegantTechnologies\Grok\Components\DemoUiChunks\BDButtonModalDialog::class);
        \Livewire::component('grok::b-e-button-poll-reset', \ElegantTechnologies\Grok\Components\DemoUiChunks\BEButtonPollReset::class);
        \Livewire::component('grok::b-f-button-modal-wire', \ElegantTechnologies\Grok\Components\DemoUiChunks\BFButtonModalWire::class);
        \Livewire::component('grok::b-f-button-modal-wire-form', \ElegantTechnologies\Grok\Components\DemoUiChunks\BFButtonModalWireForm::class);
        \Livewire::component('grok::c-a-input', \ElegantTechnologies\Grok\Components\DemoUiChunks\CAInput::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/grok.php', 'grok');
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}