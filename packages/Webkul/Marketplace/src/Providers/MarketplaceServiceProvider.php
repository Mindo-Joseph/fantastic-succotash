<?php

namespace Webkul\Marketplace\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Event;
use Illuminate\Routing\Router;
use \Webkul\Marketplace\Models\Seller;
use Webkul\Core\Tree;

class MarketplaceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/front-routes.php';

        include __DIR__ . '/../Http/admin-routes.php';

        $this->app->register(ModuleServiceProvider::class);

        $this->app->register(EventServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'marketplace');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'marketplace');

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/customers/account/partials' => resource_path('themes/default/views/customers/account/partials'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/sellers/products/add-buttons.blade.php' => resource_path('themes/default/views/products/add-buttons.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/sellers/products/price.blade.php' => resource_path('themes/default/views/products/price.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/admin/customers/edit.blade.php' => resource_path('views/vendor/admin/customers/edit.blade.php'),
        ]);

        //velocity overrided file

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/customers/account/partials' => resource_path('themes/velocity/views/customers/account/partials'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/products/view/stock.blade.php' => resource_path('themes/velocity/views/products/view/stock.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/products/add-to-cart.blade.php' => resource_path('themes/velocity/views/products/add-to-cart.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/customers/account/index.blade.php' => resource_path('themes/velocity/views/customers/account/index.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/sellers/products/add-buttons.blade.php' => resource_path('themes/velocity/views/products/add-buttons.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/sellers/products/price.blade.php' => resource_path('themes/velocity/views/products/price.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../../publishable/assets/' => public_path('themes/velocity/assets'),
        ], 'public');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.customer'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }
}