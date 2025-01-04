<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon; // Import Carbon để làm việc với thời gian

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('cdsyncs_users')->insert([
            [
                'username' => 'user_one',
                'password' => bcrypt('password1'), // Mật khẩu được mã hóa
                'email' => 'userone@example.com',
                'social_id' => null, // Nếu không có social_id
                'role' => 'user', // Hoặc 'admin', 'editor', tùy thuộc vào yêu cầu
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'user_two',
                'password' => bcrypt('password2'),
                'email' => 'usertwo@example.com',
                'social_id' => null,
                'role' => 'admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Bạn có thể thêm nhiều người dùng hơn ở đây
        ]);
    }
}
