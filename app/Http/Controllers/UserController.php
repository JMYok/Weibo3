<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    
    //中间件过滤
    public function __construct()
    {
        $this->middleware('auth',[
            'except'=>['show','create','store','index'],
        ]);

        $this->middleware('guest',[
            'only'=>['create'],
        ]);
    }

    //列出所有用户
   public function index()
   {
       $users = User::paginate(10);
       return view('user.index',compact('users'));
   }

   //进入注册页面
    public function create()
    {
        return view('user.create');
    }

   //用户注册
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', compact('user'));
    }

   //个人主页
    public function show(User $user)
    {
        return view('user.show',compact('user'));
    }

    //进入编辑页面
    public function edit(User $user)
    {
        $this->authorize('update',$user);
        return view('user.edit',compact('user'));
    }

   
   //处理编辑请求
    public function update(User $user,Request $request)
    {
      $this->authorize('update',$user);
      $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
            //confirmed 保证两次输入值相同
        ]);
      
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');   

        return redirect()->route('users.show', $user->id);
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }
}
