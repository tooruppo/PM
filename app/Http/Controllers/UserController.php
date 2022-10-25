<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Intervention\Image\Facades\Image; //追加
use App\Services\CheckExtensionServices; //追加
use App\Services\FileUploadServices; //追加

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findorFail($id);

        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findorFail($id);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {

        $user = User::findorFail($id);

        if (!is_null($request['img_name'])) {
            $imageFile = $request['img_name'];

            $list = FileUploadServices::fileUpload($imageFile);
            list($extension, $fileNameToStore, $fileData) = $list;

            $data_url = CheckExtensionServices::checkExtension($fileData, $extension);
            $image = Image::make($data_url);
            $image->resize(400, 400)->save(storage_path() . '/app/public/images/' . $fileNameToStore);

            $user->img_name = $fileNameToStore;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->employee_num = $request->employee_num;
        $user->department = $request->department;
        $user->section = $request->section;
        $user->employment_status = $request->employment_status;

        $user->save();

        return redirect('home');
    }
}
