<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Dayworkhour;
use App\Monthworkhour;
use App\Services\CheckExtensionServices; //追加
use App\Services\FileUploadServices; //追加

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Intervention\Image\Facades\Image; //追加

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'employee_num' => ['string', 'min:6', 'max:6', 'unique:users'],
            'department' => ['string'],
            'section' => ['string'],
            'employment_status' => ['string'],
            'img_name' => ['file', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2000'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {   
        $imageFile = $data['img_name'];

        $list = FileUploadServices::fileUpload($imageFile); //変更

        list($extension, $fileNameToStore, $fileData) = $list; //変更

        $data_url = CheckExtensionServices::checkExtension($fileData, $extension); //ここを変更しています。
        
        $image = Image::make($data_url);
        
        $image->resize(400,400)->save(storage_path() . '/app/public/images/' . $fileNameToStore );

        Dayworkhour::create([
            'employee_num' => $data['employee_num'],
            'date' =>date("Y-m-d"),
        ]);


        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'employee_num' => $data['employee_num'],
            'department' => $data['department'],
            'section' => $data['section'],
            'employment_status' => $data['employment_status'],
            'img_name' => $fileNameToStore, //　パスを削除してファイル名だけに変更
        ]);
    }
}
