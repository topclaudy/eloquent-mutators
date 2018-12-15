<?php

namespace Awobaz\Mutator\Console;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mutators:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the default mutators';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Eloquent Mutators Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'mutators-provider']);

        $this->comment('Publishing Eloquent Mutators Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'mutators-config']);

        $this->registerMutatorServiceProvider();

        $this->info('Eloquent Mutators scaffolding installed successfully.');
    }

    /**
     * Register the Mutator service provider in the application configuration file.
     *
     * @return void
     */
    protected function registerMutatorServiceProvider()
    {
        $namespace = str_replace_last('\\', '', $this->getAppNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\MutatorServiceProvider::class')) {
            return;
        }

        file_put_contents(config_path('app.php'), str_replace("{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL, "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL."        {$namespace}\Providers\MutatorServiceProvider::class,".PHP_EOL, $appConfig));

        file_put_contents(app_path('Providers/MutatorServiceProvider.php'), str_replace("namespace App\Providers;", "namespace {$namespace}\Providers;", file_get_contents(app_path('Providers/MutatorServiceProvider.php'))));
    }
}
