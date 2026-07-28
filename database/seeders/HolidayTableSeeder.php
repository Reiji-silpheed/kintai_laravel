<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HolidayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param=[
            'yyyymmdd'=>'2025-07-01',
            'holiday_name'=>'会社創立記念日',
            'created_at'=>Carbon::now('Asia/Tokyo'),
            'updated_at'=>Carbon::now('Asia/Tokyo')
        ];
        DB::table('holidays')->insert($param);
        $param=[
            'yyyymmdd'=>'2025-08-13',
            'holiday_name'=>'夏季休暇',
            'created_at'=>Carbon::now('Asia/Tokyo'),
            'updated_at'=>Carbon::now('Asia/Tokyo')
        ];
        DB::table('holidays')->insert($param);
        $param=[
            'yyyymmdd'=>'2025-08-14',
            'holiday_name'=>'夏季休暇',
            'created_at'=>Carbon::now('Asia/Tokyo'),
            'updated_at'=>Carbon::now('Asia/Tokyo')
        ];
        DB::table('holidays')->insert($param);
        $param=[
            'yyyymmdd'=>'2025-08-15',
            'holiday_name'=>'夏季休暇',
            'created_at'=>Carbon::now('Asia/Tokyo'),
            'updated_at'=>Carbon::now('Asia/Tokyo')
        ];
        DB::table('holidays')->insert($param);
        $param=[
            'yyyymmdd'=>'2026-07-01',
            'holiday_name'=>'会社創立記念日',
            'created_at'=>Carbon::now('Asia/Tokyo'),
            'updated_at'=>Carbon::now('Asia/Tokyo')
        ];
        DB::table('holidays')->insert($param);
    }
}
