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
                    $subTmp['drop'] = false;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'route' => 'required',
            'icon' => 'required',
            'sort' => 'numeric',
            'description' => 'required',
            'hide' => 'numeric'
        ], [
            'name.required' => '名称必须填写',
            'name.max' => '名称最长为255字符',
            'route.required' => '路由必须填写',
            'icon.required' => '图表必须填写',
            'sort.numeric' => '排序必须为数字',
            'description.required' => '描述必须填写',
            'hide.numeric' => '是否隐藏字段必须为数字'
        ]);

        $level = $request->input('level');
        if (intval($level) > 1) {
            return response()->json($result = ['code' => -1, 'desc' => '不允许有3级菜单', 'result' => []]);
        }
        $data = $request->except('level');

        $menuModel = new Menu();
        $menu = $menuModel->create($data);
        if ($menu) {
            $result = ['code' => 0, 'desc' => '', 'result' => []];
        } else {
            $result = ['code' => -1, 'desc' => '菜单创建失败', 'result' => []];
        }
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');
        if ($id == 0) {

        }

        $menuModel = new Menu();

        if ($menuModel->hasChildren($id)) {
            $result = ['code' => -1, 'desc' => '请先删除子菜单'];
            return response()->json($result);
        }
        $menu = $menuModel->destroy($id);
        if ($menu) {
            $result = ['code' => 0, 'result' => $menu];
        } else {
            $result = ['code' => -1, 'desc' => '删除失败'];
        }
        return response()->json($result);
    }

    public function changeParent(Request $request) {
        $input = $request->input();
        if (!array_key_exists('id', $input) || $id = $input['id'] == 0) {
            return response()->json(['code' => -1, 'desc' => '不能移动到根目录外']);
        }
        $menuModel = new Menu();
        $menuModel->where('id', $input['id'])->update(['parent_id' => $input['targetId']]);
        return response()->json(['code' => 0]);
    }
}
