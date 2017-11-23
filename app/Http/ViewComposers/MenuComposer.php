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
        return $url = url()->current();
        preg_match('/\/(\?|#)?$/', $url, $pregArr);
        return $pregArr;
    }
}
