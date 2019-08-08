<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Estate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function references()
    {
        $estates =new Estate();

        $amlak79_count = $estates->where('reference',true)->whereDoesntHave('experts')->count();
        $personal_count = $estates->where('reference',false)->count();
        $experts = Admin::where('role','expert')->with('estates')->get();
        return view('admin.reports.index',compact('amlak79_count','personal_count','experts'));
    }
}
