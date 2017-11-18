<?php

namespace App\Http\ViewComposers;

use App\Models\Menu;
use Illuminate\Contracts\View\View;

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
        $view->with(compact('menus'));
    }
}
