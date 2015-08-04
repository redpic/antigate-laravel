<?php
    namespace Redpic\Antigate;
    
    use Illuminate\Support\ServiceProvider;

    class AntigateServiceProvider extends ServiceProvider
    {
        /**
         * Boot the service provider.
         *
         * @return void
         */
        public function boot()
        {
            $this->publishes([
                __DIR__ . '/../config/antigate.php' => config_path('antigate.php')
            ]);
        }

        /**
         * Register the service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->mergeConfigFrom(__DIR__ . '/../config/antigate.php', 'antigate');
        }
    }