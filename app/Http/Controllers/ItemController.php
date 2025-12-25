<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Item;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::select('id', 'name', 'price', 'image')->get();
        return response(['data' => $items], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif',
        ]);
        $data = $request->except('image');
        if($request->hasFile('image')){
            $imagePath = $request->file('image');
            $originalName = pathinfo($imagePath->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $imagePath->getClientOriginalExtension();
            $newName = Carbon::now()->timestamp . '_' . Str::slug($originalName) . '.' . $extension;
            Storage::disk('public')->putFileAs('items', $imagePath, $newName);
            $data['image'] = $newName;
        }

        $item = Item::create($data);
        return response(['data' => $item], 201);
    }
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif',
        ]);
        $data = $request->except('image');
        if($request->hasFile('image')){
            if($item->image){
                Storage::disk('public')->delete('items/' . $item->image);
            }
            $imagePath = $request->file('image');
            $originalName = pathinfo($imagePath->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $imagePath->getClientOriginalExtension();
            $newName = Carbon::now()->timestamp . '_' . Str::slug($originalName) . '.' . $extension;
            Storage::disk('public')->putFileAs('items', $imagePath, $newName);
            $data['image'] = $newName;
        }

        $item->update($data);
        return response(['data' => $item], 200);
    }
}
