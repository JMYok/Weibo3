<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Mail;
use Auth;

class UserController extends Controller
{
    
    //中间件过滤
    public function __construct()
    {
        $this->middleware('auth',[
            'except'=>['show','create','store','index','confirmedEmail']
        ]);

        $this->middleware('guest',[
            'only'=>['create'],
        ]);
    }

    //在个人主页列出所有微博内容
    public function show(User $user)
    {
        $statuses = $user->statuses()->orderBy('created_at','desc')->paginate(30);

        return view('user.show',compact('user','statuses'));
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

        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
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

    //  管理员删除用户
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

    //发送邮件确认信息
    public function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'BobJiang@qq.com';
        $name = 'BobJiang';
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    public function confirmedEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', compact('user'));
    }
    
}
