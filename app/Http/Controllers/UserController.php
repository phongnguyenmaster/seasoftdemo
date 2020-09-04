<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Intervention\Image\ImageManagerStatic as Image;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);
        // Check email is exist
        // TO DO LOGIC HERE (Nếu kịp thời gian thì viết)
        $contact = new User([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ]);
        $contact->save();
        return redirect('/user')->with('success', 'User saved!');
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
        $user = User::find($id);
        return view('user.edit', compact('user'));
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
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);
        // Check email is exist
        // TO DO LOGIC HERE (Nếu kịp thời gian thì viết)
        $user = User::find($id);
        $user->name =  $request->get('name');
        $user->email = $request->get('email');
        //$user->password = $request->get('password');
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $extension = $request->file->getClientOriginalExtension();  //Get Image Extension
            $fileName = uniqid() . '.' . $extension;  //Concatenate both to get FileName (eg: file.jpg)
            $path = public_path() . '/avatar/';
            //$file->move($path, $fileName);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect('/user')->with('success', 'User deleted!');
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
            $dataResult['message'] = __('not_exist', ['attribute' => __('object.user')]);
            return $dataResult;
        }
        return $user;
    }
    private function checkIfUserExist($email)
    {
        $user = User::where('email', $email)->first();
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
