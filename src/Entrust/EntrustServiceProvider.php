<?php namespace Zizaco\Entrust;

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Zizaco\Entrust
 */

use Illuminate\Support\ServiceProvider;

class EntrustServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('entrust.php'),
        ]);

        // Register commands
        $this->commands('command.entrust.migration');
        $this->commands('command.entrust.migration');
        $this->commands('command.entrust.classes');
        $this->commands('command.entrust.permission');
        $this->commands('command.entrust.permission.list');
        $this->commands('command.entrust.role');
        $this->commands('command.entrust.role.list');
        $this->commands('command.entrust.role.attachuser');
        $this->commands('command.entrust.role.attachpermission');

        // Register blade directives
        $this->bladeDirectives();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerEntrust();

        $this->registerCommands();

        $this->mergeConfig();
    }

    /**
     * Register the blade directives
     *
     * @return void
     */
    private function bladeDirectives()
    {
        // Call to Entrust::hasRole
        \Blade::directive('role', function($expression) {
            return "<?php if (\\Entrust::hasRole{$expression}) : ?>";
        });

        \Blade::directive('endrole', function($expression) {
            return "<?php endif; // Entrust::hasRole ?>";
        });

        // Call to Entrust::can
        \Blade::directive('permission', function($expression) {
            return "<?php if (\\Entrust::can{$expression}) : ?>";
        });

        \Blade::directive('endpermission', function($expression) {
            return "<?php endif; // Entrust::can ?>";
        });

        // Call to Entrust::ability
        \Blade::directive('ability', function($expression) {
            return "<?php if (\\Entrust::ability{$expression}) : ?>";
        });

        \Blade::directive('endability', function($expression) {
            return "<?php endif; // Entrust::ability ?>";
        });
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerEntrust()
    {
        $this->app->bind('entrust', function ($app) {
            return new Entrust($app);
        });
        
        $this->app->alias('entrust', 'Zizaco\Entrust\Entrust');
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->app->singleton('command.entrust.migration', function ($app) {
            return new MigrationCommand();
        });
        $this->app->singleton('command.entrust.classes', function ($app) {
            return new ClassCreatorCommand();
        });
        $this->app->singleton('command.entrust.role', function ($app) {
            return new CreateRoleCommand();
        });
        $this->app->singleton('command.entrust.permission', function ($app) {
            return new CreatePermissionCommand();
        });
        $this->app->singleton('command.entrust.permission.list', function ($app) {
            return new PermissionListCommand();
        });
        $this->app->singleton('command.entrust.role.list', function ($app) {
            return new RoleListCommand();
        });
        $this->app->singleton('command.entrust.role.attachpermission', function ($app) {
            return new RoleAttachPermissionCommand();
        });
        $this->app->singleton('command.entrust.role.attachuser', function ($app) {
            return new RoleAttachUserCommand();
        });
    }

    /**
     * Merges user's and entrust's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'entrust'
        );
    }

    /**
     * Get the services provided.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.entrust.migration',
            'command.entrust.classes',
            'command.entrust.role',
            'command.entrust.role.list',
            'command.entrust.role.attachuser',
            'command.entrust.role.attachpermission',
            'command.entrust.permission',
            'command.entrust.permission.list',
        ];
    }
}
