<?php

namespace App\Http\Controllers\Admin;

use App\Input;
use App\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TypeController extends Controller
{
    public function createTypeFields(){
        $types = Type::pluck('name','id')->toArray();
        $fields = Input::pluck('label','id')->toArray();
        $concats =DB::table('field_type')->leftJoin('fields','field_type.field_id','=','fields.id')
            ->join('types','field_type.type_id','=','types.id')
            ->select(['types.id as id','fields.id as field_id','types.name as name','fields.label as label','category','order'])->get();
//            ->whereIn('category_tender.category_id',$cat_ids)
//            ->select(['tenders.id','tenders.*']);
        return view('admin.type.create-type-fields' , compact('types','fields','concats'));
    }

    public function storeTypeFields(Request $request)
    {
        $type = Type::find($request->type);
        $type->fields()->attach($request->field,['category'=>$request->category , 'order'=>$request->order]);
        return redirect()->action('Admin\TypeController@createTypeFields');
    }

    public function destroy($type_id , $field_id)
    {
        $type = Type::find($type_id);
        $type->fields()->detach($field_id);
        return redirect()->action('Admin\TypeController@createTypeFields');
    }
}
