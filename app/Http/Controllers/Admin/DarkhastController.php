<?php

namespace App\Http\Controllers\Admin;

use App\Darkhast;
use App\Email;
use App\Estate;
use App\Region;
use App\Type;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPSTORM_META\map;

class DarkhastController extends Controller
{

    public function index()
    {
        $darkhasts = Darkhast::with('user')->latest()->paginate(10);
        $regions = Region::pluck('name','id')->toArray();
        $types = Type::pluck('name','id')->toArray();
        return view('admin.darkhast.ajax',compact('darkhasts','regions','types'));
    }

    public function destroy($id)
    {
        $darkhadt = Darkhast::find($id)->delete();
        return redirect()->action('Admin\DarkhastController@index');
    }


}
