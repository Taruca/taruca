<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = '';
        if ($request->has('search')) {
            $search = $request->input('search');
        }
        $users = User::select('id', 'name', 'email', 'created_at')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' .$search .'%')->orWhere('email', 'like', '%' .$search . '%');
            })->paginate(10);
        return view('backend.users', compact('users', 'search'));
    }

    /**
     * Display the specified resource.
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ], [
            'id.required' => '必须传入id'
        ]);
        $id = $request->input('id');
        $user = User::select('id', 'name')->find($id);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:20',
            'id' => 'required'
        ], [
            'name.required' => '姓名必须填写',
            'name.max' => '姓名长度最大为20',
            'id.required' => 'id必须传入'
        ]);
        $data = $request->all();
        return User::where('id', $data['id'])->update(['name' => $data['name']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @return int
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        $id = $request->input('id');
        return User::destroy($id);
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
            'name' => 'required|max:20|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => '姓名必须填写',
            'name.max' => '姓名最大长度为20个字符',
            'name.unique' => '姓名已存在',
            'email.required' => '邮箱必须填写',
            'email.email' => '邮箱格式不正确',
            'email.unique' => '邮箱已存在',
            'password.required' => '密码必须填写',
            'password.min' => '密码长度最小为6位',
            'password.confirmed' => '密码和确认密码不一致'
        ]);
        $data =$request->all();
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
    }
}
