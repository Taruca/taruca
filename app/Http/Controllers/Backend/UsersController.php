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
            'name' => 'required|max:255',
            'id' => 'required'
        ], [
            'name.required' => '姓名必须填写',
            'name.max' => '姓名长度最大为255',
            'id.required' => 'id必须传入'
        ]);
        $data = $request->all();
        return User::where('id', $data['id'])->update(['name' => $data['name']]);
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
