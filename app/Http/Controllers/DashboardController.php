<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $total_request = DB::table('statistics')->count();
        $total_failed = DB::table('statistics')->where('countryCode', null)->count();
        $fail_rate = number_format((DB::table('statistics')->where('countryCode', null)->count()/DB::table("statistics")->count())*100, 2);
        $unique_request = DB::table('statistics')->distinct('query')->count('query');

        $data = [
            'total_request' =>$total_request,
            'total_failed' =>$total_failed,
            'fail_rate' =>$fail_rate,
            'unique_request' =>$unique_request,
        ];

        return response()->json($data);
    }
}
