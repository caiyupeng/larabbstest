<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests\UserRequest;

class UsersController extends Controller
{

    public function __construct()
    {
        //限制游客访问
        $this->middleware('auth', ['except' => ['show']]);
    }


    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);//现在不是本人不能访问
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user,ImageUploadHandler $uploader)
    {
        $this->authorize('update', $user);
        $data = $request->all();

        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'avatars', $user->id, 362);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);

        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }


}
