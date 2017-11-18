<?php

namespace App\Http\Controllers\Backend;

use App\Models\Menu;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MenusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.menus');
    }

    /*
     * 获取全部菜单信息
     * @return \Illuminate\Http\Response
     */
    public function getMenus() {
        $model = new Menu;
        $menus = $model->getAllMenus()->toArray();
        $tree = [];
        foreach ($menus[0] as $menu) {
            $tmp['id'] = $menu['id'];
            $tmp['name'] = $menu['name'];
            $tmp['open'] = true;
            $tmp['children'] = [];
            $tmp['level'] = 1;
            $menuId = $menu['id'];
            if (array_key_exists($menuId, $menus) && count($menus[$menuId]) > 0) {
                foreach ($menus[$menuId] as $subMenu) {
                    $subTmp = [];
                    $subTmp['name'] = $subMenu['name'];
                    $subTmp['id'] = $subMenu['id'];
                    $subTmp['level'] = 2;
                    $tmp['children'][] = $subTmp;
                }
            }
            $tree[] = $tmp;
        }
        $tree = [
            'id' => 0,
            'name' => '根目录',
            'open' => true,
            'children' => $tree,
            'level' => 0
        ];
        return response()->json($tree);
    }

    /*
     * 按照菜单id获取菜单信息
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function getMenu(Request $request) {
        $id = $request->input('id');
        $menu = Menu::findOrFail($id);

        return response()->json($menu);
    }

    public function setMenu(Request $request) {
        $data = $request->except('_token');
        $id = $data['id'];
        if (!array_key_exists('hide', $data)) {
            $data['hide'] = 0;
        }
        Menu::where('id', $id)->update($data);

        $result = ['code' => 0];
        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
