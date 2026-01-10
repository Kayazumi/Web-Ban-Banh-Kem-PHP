<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::orderByDesc('created_at');
        $status = $request->get('status');
        if ($status) $query->where('status', $status);
        $items = $query->get();
        return response()->json(['success' => true, 'data' => ['promotions' => $items]]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:100|unique:promotions,promotion_code',
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
            return response()->json([
                'success' => false, 
                'message' => 'Invalid data', 
                'errors' => $validator->errors()
            ], 400);
        }
        
        // Map request fields to database columns
        $promo = Promotion::create([
            'promotion_code' => $request->code,
            'promotion_name' => $request->title,
            'description' => $request->description ?? $request->title,
            'promotion_type' => $request->type === 'percent' ? 'percent' : ($request->type === 'fixed' ? 'fixed_amount' : 'free_shipping'),
            'discount_value' => $request->value,
            'min_order_value' => $request->min_order,
            'quantity' => $request->quantity ?? 0,
            'used_count' => 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->get('status', 'active'),
            'image_url' => $request->image_url,
        ]);
        
        return response()->json(['success' => true, 'data' => ['promotion' => $promo]], 201);
    }

    public function show($id)
    {
        $promo = Promotion::find($id);
        if (!$promo) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
        return response()->json(['success' => true, 'data' => ['promotion' => $promo]]);
    }

    public function update(Request $request, $id)
    {
        $promo = Promotion::find($id);
        if (!$promo) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
        
        $updateData = [];
        
        if ($request->has('title')) $updateData['promotion_name'] = $request->title;
        if ($request->has('code')) $updateData['promotion_code'] = $request->code;
        if ($request->has('description')) $updateData['description'] = $request->description;
        if ($request->has('type')) {
            $updateData['promotion_type'] = $request->type === 'percent' ? 'percent' : ($request->type === 'fixed' ? 'fixed_amount' : 'free_shipping');
        }
        if ($request->has('value')) $updateData['discount_value'] = $request->value;
        if ($request->has('min_order')) $updateData['min_order_value'] = $request->min_order;
        if ($request->has('quantity')) $updateData['quantity'] = $request->quantity;
        if ($request->has('start_date')) $updateData['start_date'] = $request->start_date;
        if ($request->has('end_date')) $updateData['end_date'] = $request->end_date;
        if ($request->has('status')) $updateData['status'] = $request->status;
        if ($request->has('image_url')) $updateData['image_url'] = $request->image_url;
        
        $promo->update($updateData);
        $promo->refresh();
        
        return response()->json(['success' => true, 'data' => ['promotion' => $promo]]);
    }

    public function destroy($id)
    {
        $promo = Promotion::find($id);
        if (!$promo) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
        $promo->delete();
        return response()->json(['success' => true]);
    }
}
