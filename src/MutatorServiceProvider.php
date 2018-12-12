<?php namespace Awobaz\Mutator;

use Awobaz\Mutator\Console\InstallCommand;
use Awobaz\Mutator\Console\PublishCommand;
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
        $this->mergeConfigFrom(
            __DIR__.'/../config/mutators.php', 'mutators'
        );

        $this->commands([
            InstallCommand::class,
            PublishCommand::class,
        ]);
    }
}
