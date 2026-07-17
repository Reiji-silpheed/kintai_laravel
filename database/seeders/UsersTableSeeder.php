<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param=[
            'user_no'=>'001',
            'name'=>'研修太郎',
            'email'=>'kensyuu.tarou@example.com',
            'email_verified_at'=>now(),
            'start_date'=>'2020-04-01',
            'password'=>'pass',
            'role_cd'=>'1',
            'created_at'=>Carbon::now('Asia/Tokyo'),
            'updated_at'=>Carbon::now('Asia/Tokyo'),
        ];
        DB::table('users')->insert($param);
        $param=[
            'user_no'=>'002',
            'name'=>'研修次郎',
            'email'=>'kensyuu.jirou@example.com',
            'email_verified_at'=>now(),
            'start_date'=>'2020-04-01',
            'password'=>'pass',
            'role_cd'=>'0',
            'created_at'=>Carbon::now('Asia/Tokyo'),
            'updated_at'=>Carbon::now('Asia/Tokyo'),
        ];
        DB::table('users')->insert($param);
        $param=[
            'user_no'=>'003',
            'name'=>'研修三郎',
            'email'=>'kensyuu.saburou@example.com',
            'email_verified_at'=>now(),
            'start_date'=>'2020-04-01',
            'password'=>'pass',
            'role_cd'=>'0',
            'created_at'=>Carbon::now('Asia/Tokyo'),
            'updated_at'=>Carbon::now('Asia/Tokyo'),
        ];
        DB::table('users')->insert($param);
        $param=[
            'user_no'=>'004',
            'name'=>'研修四子',
            'email'=>'kensyuu.yoshiko@example.com',
            'email_verified_at'=>now(),
            'start_date'=>'2020-04-01',
            'password'=>'pass',
            'role_cd'=>'1',
            'created_at'=>Carbon::now('Asia/Tokyo'),
            'updated_at'=>Carbon::now('Asia/Tokyo'),
        ];
        DB::table('users')->insert($param);
        $param=[
            'user_no'=>'005',
            'name'=>'研修五子',
            'email'=>'kensyuu.itsuko@example.com',
            'email_verified_at'=>now(),
            'start_date'=>'2020-04-01',
            'password'=>'pass',
            'role_cd'=>'0',
            'created_at'=>Carbon::now('Asia/Tokyo'),
            'updated_at'=>Carbon::now('Asia/Tokyo'),
        ];
        DB::table('users')->insert($param);
        $param=[
            'user_no'=>'006',
            'name'=>'研修六太',
            'email'=>'kensyuu.rokuta@example.com',
            'email_verified_at'=>now(),
            'start_date'=>'2020-04-01',
            'password'=>'pass',
            'role_cd'=>'0',
            'created_at'=>Carbon::now('Asia/Tokyo'),
            'updated_at'=>Carbon::now('Asia/Tokyo'),
        ];
        DB::table('users')->insert($param);
    }
}
