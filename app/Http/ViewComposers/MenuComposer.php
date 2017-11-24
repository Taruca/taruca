<?php

namespace App\Http\ViewComposers;

use App\Models\Menu;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Route;

class MenuComposer
{
    /**
     * building data to the view
     *
     * @param  View $view
     *+
     *
     * @return void
     */
    public function compose(View $view)
    {
        $model = new Menu;
        $menus = $model->getActiveMenus();
        $activeRoute = $this->getCurUrl();
        $view->with(compact('menus', 'activeRoute'));
    }


    private function getCurUrl() {
        $url = url()->current();
        $url = str_replace('http://', '', $url);
        $url = str_replace('https://', '', $url);
        $index = strpos($url, '/');
        if ($index > 0 && $index < strlen($url) - 1) {
            return substr($url, $index + 1);
        } else {
            return config('backend.menus.defaultMenu');
        }
    }
}
