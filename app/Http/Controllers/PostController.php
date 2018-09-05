<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\categories;
use App\posts;
use App\likes;
use App\dislikes;
use App\comments;
use App\User;
use App\profiles;
use Auth;

class PostController extends Controller
{
    public function post(){
        $categories = categories::all();
    	return view('posts.post', ['categories' => $categories]);
    }

    public function addPost(Request $request){
    	$this->validate($request, [
            'post_title' => 'required',
            'post_body' => 'required',
            'category_id' => 'required',
            'post_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4000'
    	]);
        $posts = new posts;
        $posts->user_id = Auth::user()->id;
        $posts->post_title = $request->input('post_title');
        $posts->post_body = $request->input('post_body');
        $posts->category_id = $request->input('category_id');
        if (Input::hasFile('post_image')) {
        	$file = Input::file('post_image');
        	$file->move(public_path().'/uploads', $file->getClientOriginalName());
        	$url = URL::to('/').'/uploads/'.$file->getClientOriginalName();
        }
        $posts->post_image = $url;
        $posts->save();
        return redirect('/home')->with('status', 'Post Published successfully'); 
    }

    public function view($post_id){
        $categories = categories::all();
        $likes = likes::where('post_id', $post_id)->count();
        $dislikes = dislikes::where('post_id', $post_id)->count();
        $posts = posts::where('id', '=', $post_id)->get();
        $comments = DB::table('users')
          ->join('comments', 'users.id', '=', 'comments.user_id')
          ->join('posts', 'comments.post_id', '=', 'posts.id')
          ->select('users.name', 'comments.*')
          ->where(['posts.id' => $post_id])
          ->get(); 
        return view('posts.view', ['posts' => $posts, 
                                   'categories' => $categories,
                                   'likes' => $likes,
                                   'dislikes' => $dislikes,
                                   'comments' => $comments
                                  ]);
    }

    public function edit($post_id){
        $posts = posts::find($post_id);
        $categories = categories::all();
        $category = categories::find($posts->category_id);
        return view('posts.edit', ['categories' => $categories,
                                   'posts' => $posts,
                                   'category' => $category
                                  ]);
    }

    public function delete($post_id){
        posts::where('id', $post_id)->delete();
        return redirect('/home')->with('status', 'Post Deleted successfully');

    }

    public function like($post_id){
        $current_user_id = Auth::user()->id;
        $check_user = likes::where(['user_id' => $current_user_id, 'post_id' => $post_id])->first();
        if (empty($check_user)) {
            $likes = new likes;
            $likes->user_id = $current_user_id;
            $likes->post_id = $post_id;
            $likes->email = Auth::user()->email;
            $likes->save();
            return redirect("/view/{$post_id}");
        }else{
            return redirect("/view/{$post_id}");
        }
    }

    public function dislike($post_id){
        $current_user_id = Auth::user()->id;
        $check_user = dislikes::where(['user_id' => $current_user_id,
                                       'post_id' => $post_id
                                     ])->first();
        if (empty($check_user)) {
            $dislikes = new dislikes;
            $dislikes->user_id = $current_user_id;
            $dislikes->post_id = $post_id;
            $dislikes->email = Auth::user()->email;
            $dislikes->save();
            return redirect("/view/{$post_id}");
        }else{
            return redirect("/view/{$post_id}");
        }
    }

    public function editPost(Request $request, $post_id){
        $this->validate($request, [
            'post_title' => 'required',
            'post_body' => 'required',
            'category_id' => 'required',
            'post_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4000'
        ]);
        $posts = new posts;
        $posts->user_id = Auth::user()->id;
        $posts->post_title = $request->input('post_title');
        $posts->post_body = $request->input('post_body');
        $posts->category_id = $request->input('category_id');
        if (Input::hasFile('post_image')) {
            $file = Input::file('post_image');
            $file->move(public_path(). '/posts/', $file->getClientOriginalName());
            $url = URL::to("/") . '/posts/'. $file->getClientOriginalName();
        }
        $posts->post_image= $url;
        $data = array(
            'user_id' =>  $posts->user_id,
            'post_title' => $posts->post_title,
            'post_body' => $posts->post_body,
            'category_id' => $posts->category_id,
            'post_image' => $posts->post_image
        );
        posts::where('id', '=', $post_id)->update($data);
        return redirect('/home')->with('status', 'Post Updated successfully');
    }

    public function comment(Request $request, $id){
        $this->validate($request, [
            'comment' => 'required'
        ]);
        $comments = new comments;
        $comments->user_id = Auth::user()->id;
        $comments->post_id = $id;
        $comments->comment = $request->input('comment');
        $comments->save();
        return redirect("/view/{$id}");
    }

    public function search(Request $request){
        $user_id = Auth::user()->id;
        $profile = profiles::where(['user_id' => $user_id])->first();
        $keyword = $request->input('search');
        $posts = posts::where('post_title', 'LIKE', '%'.$keyword.'%')->get();
        return view('posts.searchposts', ['profile' => $profile,
                                          'posts' => $posts
                                         ]);
    }
}