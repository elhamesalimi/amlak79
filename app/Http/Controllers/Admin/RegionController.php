<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Http\Controllers\Controller;
use App\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {
        $cities = City::with('regions')->get();
        if (request()->ajax())
            return view('admin.cities.regions.index', compact('cities'));
        else
            return view('admin.cities.regions.ajax', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::get();
        return view('admin.cities.regions.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        //after save
//        $i=1;
//foreach (Region::where('city_id',$request->city_id)->orderBy('arrangr')->get() as $region){
//    $region->arrange = $i;
//    $region->save();
//    $i++;
//}
//        return redirect()->action('Admin\RegionController@index')->with('success', 'منطقه جدید ویرایش شد.');

        $request->validate([
            'name' => 'required|unique:regions',
            'arrange' => 'required|integer',
            'city_id' => 'required|integer'
        ]);
//        $validator = Validator::make($request->all(), [
//            'name' => 'required|unique:regions',
//        ]);
//        if ($validator->fails())
//            return response()->json([
//                'fail' => true,
//                'errors' => $validator->errors()
//            ]);
        $arrange = $request->arrange;
        $region = new Region();
        $region->city_id = $request->city_id;
        $region->name = $request->name;
        $region->arrange = $arrange;
        $region->save();
        $regions = Region::where('arrange', '>=', $arrange)->where('id', '<>', $region->id)->orderBy('arrange')->get();
        foreach ($regions as $region) {
            $region->arrange = $region->arrange + 1;
            $region->save();

        }
//        for ($i = $arrange; $i < $regions->count() + $arrange; $i++) {
//            $region = $regions->where('arrange', $i)->first();
//            if ($region) {
//                $region->arrange = $i + 1;
//                $region->save();
//                return $regions;
//            } else {
//                break;
//            }
//        }

        return redirect()->action('Admin\RegionController@index')->with('success', 'منطقه جدید ویرایش شد.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Region $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Region $region
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
    {
        $cities = City::get();
        return view('admin.cities.regions.create', compact('cities', 'region'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Region $region
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Region $region)
    {
        $request->validate([
            'name' => 'required|unique:regions,id,' . $region->id,
            'arrange' => 'required|integer',
            'city_id' => 'required|integer'
        ]);
        $arrange = $request->arrange;
        $region->city_id = $request->city_id;
        $region->name = $request->name;
        $region->arrange = $arrange;
        $region->save();
        $regions = Region::where('arrange', '>=', $arrange)->where('id', '<>', $region->id)->orderBy('arrange')->get();
        foreach ($regions as $region) {
            $region->arrange = $region->arrange + 1;
            $region->save();

        }
//        $regions = Region::with('children')->whereNull('parent_id')->get();
        $request->session()->flash('success', 'Task was successful!');

        return redirect()->action('Admin\RegionController@index')->with('success', 'منطقه جدید ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Region $region
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        $arrange = $region->arrange;
        $region->delete();
        $regions = Region::where('arrange', '>=', $arrange)->where('id', '<>', $region->id)->orderBy('arrange')->get();
        foreach ($regions as $region) {
            $region->arrange = $region->arrange - 1;
            $region->save();
        }
        return redirect()->action('Admin\RegionController@index')->with('delete-message', 'منطقه حذف شد.');

    }


}
