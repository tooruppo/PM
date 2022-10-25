<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            ['name' => '太郎',
            'email' => 'taro@taro.jp',
            'password' => Hash::make('pd123'),
            'employee_num' => '100001',
            'department' => 'デザイン部',
            'section' => 'DTP',
            'employment_status' => '正規社員',
            'img_name' => 'sample001.jpg',
            ],

            ['name' => '次郎',
            'email' => 'jiro@jiro.jp',
            'password' => Hash::make('pd123'),
            'employee_num' => '100002',
            'department' => 'デザイン部',
            'section' => 'DTP',
            'employment_status' => '契約社員',
            'img_name' => 'sample002.jpg',
            ],

            ['name' => '三郎',
            'email' => 'saburo@saburo.jp',
            'password' => Hash::make('pd123'),
            'employee_num' => '100003',
            'department' => 'デザイン部',
            'section' => 'WEB',
            'employment_status' => '正規社員',
            'img_name' => 'sample003.jpg',
            ],

            ['name' => '四郎',
            'email' => 'shiro@shiro.jp',
            'password' => Hash::make('pd123'),
            'employee_num' => '100004',
            'department' => 'デザイン部',
            'section' => 'WEB',
            'employment_status' => '契約社員',
            'img_name' => 'sample004.jpg',
            ],
        ]);
    }
}
