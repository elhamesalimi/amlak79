<?php

namespace App\Http\Controllers\Api;

use App\Email;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function activePhone($phone)
    {
        $user = User::where('phone',$phone)->first();
        $user->active = true;
        $user->save();
        return response('user active successfully');
    }

    public function getUserInformation()
    {

        $user = Auth::guard('admin-api')->check() ? Auth::guard('admin-api')->user() : Auth::guard('api')->user();
        $userInfo = new User();
        $userInfo->name = $user->name;
        $userInfo->phone = $user->phone;
        $userInfo->role = $user->role;
        $email = Auth::guard('admin-api')->check() ? $user->email :$user->emails[0]->email;
        $userInfo->email = $email;
        $telephone = $user->telephone;
        if($telephone){
            $userInfo->telephone = $telephone->telephone;
        } ;
        return $userInfo;
}

    public function getCities(){
        $cities = City::all();
        return response()->json($cities);
    }
    public function getPlans(){
        $plans = Plan::all();
        return response()->json($plans);
    }

    public function getRegions($city_id)
    {
        $regions = Region::where('city_id', $city_id)->get();
        return $regions;
    }

    public function getTypes()
    {
        $types = Type::all();
        return $types;
    }

    public function getFields($type_id , $category)
    {
        $type = Type::find($type_id);
        $fields_name = $type->fields()->wherePivot('category',$category)->pluck('name');
        $fields = ['price'=>1,'total_price'=>1,'plans'=>1, 'area'=>1];
        foreach ($fields_name as $item){
            $fields[$item] = 1;
        }
    return $fields;
    }

    public function index(Request $request)
    {
        $city = City::whereSlug($request->city)->first();
        $estates = User::where('city_id',$city->id);
        $estates = $estates->where('category', $request->category);
        $estates = $estates->where('type_id',$request->type_id);
        if($request->region_id){
            $estates = $estates->where('region_id',$request->region_id);
        }

        return response()->json($estates->get() );

        return response()->json(['message'=>'شهری با این نام وجود ندارد'],404);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            return Auth::user();
        }
//        return $request;
        return Auth::user();

        $owner = new Owner();
        $owner->name = $request->owner['name'];
        $owner->phone = $request->owner['phone'];
        if($request->owner['telephone'])
            $owner->telephone = $request->owner['telephone'];
        $owner->email = $request->owner['email'];

        $owner->save();

    }

    public function getMyUser()
    {
        $user = Auth::user();
        return $user;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
