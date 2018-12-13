<?php namespace Awobaz\Mutator;

use Awobaz\Mutator\Console\InstallCommand;
use Awobaz\Mutator\Console\PublishCommand;
use Awobaz\Mutator\Facades\Mutator as MutatorFacade;
use Illuminate\Support\ServiceProvider;

class MutatorServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
    }


    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/mutators.php' => config_path('mutators.php'),
            ], 'mutators-config');

            $this->publishes([
                __DIR__.'/../stubs/MutatorServiceProvider.stub' => app_path('Providers/MutatorServiceProvider.php'),
            ], 'mutators-provider');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();

        $this->registerCommands();

        $this->registerDefaultMutators();

        $this->mergeConfig();
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerBindings()
    {
        $this->app->singleton('mutator', function ($app) {
            return new Mutator();
        });

        $this->app->alias('mutator', 'Awobaz\Mutator\Mutator');
    }

    /**
     * Register the application commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->commands([
            InstallCommand::class,
            PublishCommand::class,
        ]);
    }

    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/mutators.php', 'mutators'
        );
    }

    private function registerDefaultMutators(){
        MutatorFacade::extend('trim_space', function($model, $value, $key){
            return trim($value);
        });

        MutatorFacade::extend('remove_extra_space', function($model, $value, $key){
            return preg_replace('/\s+/', ' ', $value);
        });
    }
}
