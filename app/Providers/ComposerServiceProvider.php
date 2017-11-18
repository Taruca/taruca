<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    private $user, $menu;

    public function __construct() {
        $this->user = [
            'shared.backend_nav'
        ];

        $this->menu = [
            'shared.backend_sidebar'
        ];
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer($this->user, function ($view) {
            $userInfo = \Auth::user();
            $view->with(compact('userInfo'));
        });

        view()->composer($this->menu, 'App\Http\ViewComposers\MenuComposer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
