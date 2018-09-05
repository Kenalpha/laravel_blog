<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\categories;
use App\posts;

class CategoryController extends Controller
{
    public function category(){
    	return view('categories.category');
    }

    public function addCategory(Request $request){
    	$this->validate($request, [
            'category' => 'required'
    	]);
    	$category = new categories;
    	$category->category = $request->input('category');
    	$category->save();
    	return redirect('/category')->with('status', 'Category saved successfully');
    }

    public function go_to_category($id){
        $categories = categories::all();
        $posts = posts::where('category_id',$id)->get();
        return view('categories.view', ['posts' => $posts, 'categories' => $categories]);
    }
}
