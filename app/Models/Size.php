<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Size extends Model
{
    use HasFactory;

    public function createSize(Request $request) {
        $name = $request['name'];
        $category = $request['category'];

        if(!Category::where('id', $category)->exists()) {
            return response()->json(['success' => false, 'msg' => 'Category does not exist.']);
        }

        $size = new \App\Models\Size();
        $size->name = $name;
        $size->category = $category;
        $size->save();

        return response()->json(['success' => true, 'msg' => $size->id]);
    }

    public function deleteSize(Request $request) {
        if(Size::where('id', $request['id'])->exists()) {
            $size = Size::where('id', $request['id'])->first();
            $size->delete();
        } else {
            return response()->json(['success' => false, 'msg' => 'Size does not exist.']);
        }

        return response()->json(['success' => true]);
    }
}
