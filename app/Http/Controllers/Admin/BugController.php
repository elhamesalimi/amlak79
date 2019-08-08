<?php

namespace App\Http\Controllers\Admin;

use App\Bug;
use App\Estate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BugController extends Controller
{
    public function index()
    {
        $estates = Estate::whereHas('bugs')->with('bugs')->where('status', 'PUBLISHED')->select('id')->get();
        return view('admin.bugs.index',compact('estates'));
    }

    public function destroy($estate_id,$bug_id)
    {
        $bug = Bug::find($bug_id);
        $bug->estates()->detach($estate_id);
        return redirect()->back()->with('message','گزارش با موفقیت حذف شد.');
    }

    public function report(Estate $estate)
    {
        $bugs = $estate->bugs->groupBy('name');
        return json_decode($bugs['price'][0]->pivot->meta);
        return $bugs;
    }
}
