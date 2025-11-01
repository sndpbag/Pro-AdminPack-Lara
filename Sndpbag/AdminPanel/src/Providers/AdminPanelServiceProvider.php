<?php

namespace Sndpbag\AdminPanel\Providers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Sndpbag\AdminPanel\Middleware\RoleMiddleware;
use Sndpbag\AdminPanel\Middleware\PermissionMiddleware;
use Sndpbag\AdminPanel\Commands\InstallCommand;
use Sndpbag\AdminPanel\Commands\SyncRoutesCommand;

class AdminPanelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // এখানে আমরা আমাদের প্যাকেজের routes, views, migrations লোড করার কোড লিখব।

         // ১. প্যাকেজের রাউট (Routes) লোড করার জন্য
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        // ২. প্যাকেজের মাইগ্রেশন (Migrations) লোড করার জন্য
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // ৩. প্যাকেজের ভিউ (Views) লোড করার জন্য
        // 'admin-panel' নামটি জরুরি, এটি ভিউগুলোকে চেনার জন্য ব্যবহৃত হবে।
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin-panel');
		$this->loadViewsFrom(__DIR__.'/../../resources/views/roles-permissions', 'dynamic-roles');

        // ৪. প্যাকেজের অ্যাসেট (CSS/JS) এবং অন্যান্য ফাইল পাবলিশ করার জন্য
        // ব্যবহারকারী `php artisan vendor:publish` কমান্ড চালালে এই ফাইলগুলো কপি হবে।
        $this->publishes([
            __DIR__.'/../../resources/assets' => public_path('vendor/admin-panel'),
        ], 'admin-panel-assets');
		
		$this->publishes([
    __DIR__.'/../../resources/views/roles-permissions' => resource_path('views/vendor/dynamic-roles'),
		], 'dynamic-roles-views');



          $this->publishes([
            __DIR__.'/../../config/admin-panel.php' => config_path('admin-panel.php'),
        ], 'admin-panel-config'); // একটি ট্যাগ দেওয়া হলো
		
		
		$router = $this->app->make(\Illuminate\Routing\Router::class);
		// নতুন নেমস্পেস ব্যবহার করুন
			$router->aliasMiddleware('role', \Sndpbag\AdminPanel\Middleware\RoleMiddleware::class);
				$router->aliasMiddleware('permission', \Sndpbag\AdminPanel\Middleware\PermissionMiddleware::class);



         // Eta config/auth.php file ke dynamically override korbe.
        Config::set('auth.providers.users.model', \Sndpbag\AdminPanel\Models\User::class);
		
		
		
		if ($this->app->runningInConsole()) {
    $this->commands([
        // নতুন নেমস্পেস ব্যবহার করুন
        \Sndpbag\AdminPanel\Commands\InstallCommand::class,
        \Sndpbag\AdminPanel\Commands\SyncRoutesCommand::class,
    ]);
			}
			
			
			$this->registerBladeDirectives();

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
          $this->mergeConfigFrom(
            __DIR__.'/../../config/admin-panel.php', 'admin-panel'
        );
    }
	
	
	 protected function registerBladeDirectives()
    {
        // @hasRole('role-slug') OR @hasRole(['role1', 'role2'])
        Blade::directive('hasRole', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->hasRole({$expression})): ?>";
        });
        Blade::directive('elsehasRole', function () {
            return '<?php else: ?>';
        });
        Blade::directive('endhasRole', function () {
            return '<?php endif; ?>';
        });

        // @hasAnyRole(['role1', 'role2'])
        Blade::directive('hasAnyRole', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->hasAnyRole({$expression})): ?>";
        });
        Blade::directive('elsehasAnyRole', function () {
            return '<?php else: ?>';
        });
        Blade::directive('endhasAnyRole', function () {
            return '<?php endif; ?>';
        });

        // @hasAllRoles(['role1', 'role2'])
        Blade::directive('hasAllRoles', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->hasAllRoles({$expression})): ?>";
        });
        Blade::directive('elsehasAllRoles', function () {
            return '<?php else: ?>';
        });
        Blade::directive('endhasAllRoles', function () {
            return '<?php endif; ?>';
        });

        // @hasPermission('perm-slug') OR @hasPermission(['perm1', 'perm2'])
        Blade::directive('hasPermission', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$expression})): ?>";
        });
        Blade::directive('elsehasPermission', function () {
            return '<?php else: ?>';
        });
        Blade::directive('endhasPermission', function () {
            return '<?php endif; ?>';
        });

        // @hasAnyPermission(['perm1', 'perm2'])
        Blade::directive('hasAnyPermission', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->hasAnyPermission({$expression})): ?>";
        });
        Blade::directive('elsehasAnyPermission', function () {
            return '<?php else: ?>';
        });
        Blade::directive('endhasAnyPermission', function () {
            return '<?php endif; ?>';
        });

        // @hasAllPermissions(['perm1', 'perm2'])
        Blade::directive('hasAllPermissions', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->hasAllPermissions({$expression})): ?>";
        });
        Blade::directive('elsehasAllPermissions', function () {
            return '<?php else: ?>';
        });
        Blade::directive('endhasAllPermissions', function () {
            return '<?php endif; ?>';
        });
    }
	
}