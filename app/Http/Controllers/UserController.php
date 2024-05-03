<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_kana' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'mail_flg' => 'required|boolean',
        ]);
    
        User::create([
            'name' => $request->name,
            'name_kana' => $request->name_kana,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'mail_flg' => $request->mail_flg,
        ]);
    
        return redirect()->route('users.index')->with('success', '新しいユーザーが登録されました。');
    }

    /**
     * ユーザー一覧を表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('role')->where('show_flg', 1)->get();
        return view('users.index', compact('users'));
    }

    /**
     * ユーザー編集フォームを表示
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * ユーザー情報を更新
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_kana' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'mail_flg' => 'required|boolean',
        ]);
    
        $user->name = $request->name;
        $user->name_kana = $request->name_kana;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->mail_flg = $request->mail_flg;
    
        if ($request->filled('password')) {
            if (Hash::check($request->password, $user->password)) {
                // 入力されたパスワードが現在のものと同じ場合は更新しない
                unset($request['password']);
            } else {
                // 入力されたパスワードが現在のものと異なる場合は更新する
                $user->password = Hash::make($request->password);
            }
        }
    
        $user->save();
    
        return redirect()->route('users.index')->with('success', 'ユーザー情報が更新されました。');
    }

     /**
     * ユーザーを論理削除
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->update(['show_flg' => 0]);

        return redirect()->route('users.index')->with('success', 'ユーザーが削除されました。');
    }

}