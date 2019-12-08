<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppSummaryController extends Controller
{
    public function index()
    {
        return view('pages.app_summary');
    }

    public function getSummary()
    {
        return DB::table('app_request_today')->orderBy('total', 'desc')->get();
    }


    public function getFilterSummmary(Request $request)
    {
        $this->validate($request, [
            'from' => 'required',
            'to' => 'required',
            'country' => 'required',
        ]);

        return DB::table('app_summaries')->whereBetween('date', [$request->from, $request->to])->where('country', $request->country)->get();
    }


    public function getCountryList()
    {
        return DB::table('apps_countries')->get();
    }
}