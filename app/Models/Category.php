<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Category extends Model
{
    use HasFactory;

    public function createCategory(Request $request) {
        $name = $request['name'];
        $parent_category = $request['parent_category'];

        if(Category::where('name', $name)->exists()) {
            return response()->json(['success' => false, 'msg' => 'Category with that name already exists.']);
        }

        $category = new \App\Models\Category();
        $category->name = $name;

        if(!empty($parent_category)) {
            if(!Category::where('id', $parent_category)->exists()) {
                return response()->json(['success' => false, 'msg' => 'Parent category does not exist.']);
            }
            $category->parent_category = $parent_category;
        }

        $category->save();
        return response()->json(['success' => true, 'msg' => $category->id]);
    }

    public function deleteCategory(Request $request) {
        if(Category::where('id', $request['id'])->exists()) {
            if(Category::where('parent_category', $request['id'])->exists()) {
                return response()->json(['success' => false, 'msg' => 'Category contains subcategories.']);
            } else {
                $category = Category::where('id', $request['id'])->first();
                $category->delete();
            }
        } else {
            return response()->json(['success' => false, 'msg' => 'Category does not exist.']);
        }

        

        return response()->json(['success' => true]);
    }
}
