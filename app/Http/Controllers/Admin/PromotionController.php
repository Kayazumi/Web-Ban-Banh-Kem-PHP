<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $q = DB::table('promotions')->orderByDesc('created_at');
        $status = $request->get('status');
        if ($status) $q->where('status', $status);
        $items = $q->get();
        return response()->json(['success' => true, 'data' => ['promotions' => $items]]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:100|unique:promotions,code',
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'value' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'quantity' => 'nullable|integer',
            'min_order' => 'nullable|numeric',
            'image_url' => 'nullable|string',
            'description' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid data', 'errors' => $validator->errors()], 400);
        }
        $id = DB::table('promotions')->insertGetId(array_merge($request->only([
            'code','title','type','value','start_date','end_date','quantity','min_order','image_url','description'
        ]), ['status' => $request->get('status','active') ,'created_at' => now(), 'updated_at' => now()]));
        $promo = DB::table('promotions')->where('id', $id)->first();
        return response()->json(['success' => true, 'data' => ['promotion' => $promo]], 201);
    }

    public function show($id)
    {
        $p = DB::table('promotions')->where('id', $id)->first();
        if (!$p) return response()->json(['success'=>false,'message'=>'Not found'],404);
        return response()->json(['success'=>true,'data'=>['promotion'=>$p]]);
    }

    public function update(Request $request, $id)
    {
        $p = DB::table('promotions')->where('id', $id)->first();
        if (!$p) return response()->json(['success'=>false,'message'=>'Not found'],404);
        $data = $request->only(['title','type','value','start_date','end_date','quantity','min_order','image_url','description','status']);
        $data['updated_at'] = now();
        DB::table('promotions')->where('id', $id)->update($data);
        $p2 = DB::table('promotions')->where('id', $id)->first();
        return response()->json(['success'=>true,'data'=>['promotion'=>$p2]]);
    }

    public function destroy($id)
    {
        $p = DB::table('promotions')->where('id', $id)->first();
        if (!$p) return response()->json(['success'=>false,'message'=>'Not found'],404);
        DB::table('promotions')->where('id', $id)->delete();
        return response()->json(['success'=>true]);
    }
}


