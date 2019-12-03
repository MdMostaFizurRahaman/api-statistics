<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CountrySummaryController extends Controller
{
    public function index()
    {
        $no = 1;
        $countries = DB::table('country_requests_today')->get();
        return view('pages.country_summary')->with('countries', $countries)->with('no', $no);
    }

    public function getSummary()
    {
        return DB::table('country_requests_today')->get();
    }
}
