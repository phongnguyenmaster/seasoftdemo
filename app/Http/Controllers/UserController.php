<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function getListUser($page)
    {
        $validator = Validator::make(array('page' => $page), [
            'page' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return abort(404);
        }
        $user = new User();
        return $user->getListUser($page);
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'email' => 'required|max:100|email',
        ]);
        if ($validator->fails()) {
            return redirect('user/' . Auth::user()->id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }
        // Check email is exist
        $existUser = $this->checkUserExist($request->get('email'));
        if ($existUser) {
            $validator->errors()->add('email', __('validation.is_exist', ['attribute' => __('field.email')]));
            return redirect('user/' . Auth::user()->id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }
        $user = Auth::user();
        $user->name =  $request->get('name');
        $user->email = $request->get('email');
        $user->updated_at = date('Y-m-d H:i:s');
        //$user->password = $request->get('password');
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $extension = $request->file->getClientOriginalExtension();  //Get Image Extension
            $fileName = uniqid() . '.' . $extension;  //Concatenate both to get FileName (eg: file.jpg)
            $path = public_path() . '/avatar/';
            // Resize and upload new avatar
            $image_resize = Image::make($file->getRealPath());
            $image_resize->fit(300, 300);
            $image_resize->save($path . $fileName);
            // Delete old avatar
            if (is_file($path . $user->avatar)) {
                unlink($path . $user->avatar);
            }
            $user->avatar = $fileName;
        }
        $user->save();
        return redirect('/home')->with('status', 'User updated!');
    }

    public function getUserInfo(Request $request)
    {
        $dataResult = array('status' => 1, 'message' => '');
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $dataResult['status'] = 0;
            $dataResult['message'] = __('validation.invalid_data');
            return $dataResult;
        }
        $id =  $request->get('id');
        $user = $this->getUserInfoById($id);
        if (!$user) {
            $dataResult['status'] = 0;
            $dataResult['message'] = __('not_exist', ['attribute' => __('field.user')]);
            return $dataResult;
        }
        return $user;
    }

    private function checkUserExist($email)
    {
        $user = User::where('email', $email)->where('id', '!=', Auth::user()->id)->first();
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }
    private function getUserInfoById($id)
    {
        $user = User::where('id', $id)->first();
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }
}
