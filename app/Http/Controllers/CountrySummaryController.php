<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CountrySummaryController extends Controller
{
    public function index()
    {
        return view('pages.country_summary');
    }

    public function getSummary()
    {
        return DB::table('country_requests_today')->orderBy('total', 'desc')->get();
    }

    public function getHourlySummary(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'country' => 'required',
        ]);

        return DB::table('country_summaries')->where('date', $request->date)->where('country', $request->country)->get();
    }

    public function getCountryList()
    {
        return DB::table('apps_countries')->get();
    }
}