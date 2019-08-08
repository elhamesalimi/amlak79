<?php

namespace App\Http\Controllers\Api;

use App\Bug;
use App\Estate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BugController extends Controller
{

    public function storeReportBug(Request $request)
    {
        $estate = Estate::find($request->estate_id);
        $estate->bugs()->attach($request->bug, ['meta' =>$request->meta]);
        return response('insert successfully', 200);
    }
}
