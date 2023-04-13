<?php

namespace App\Http\Controllers\API;

use App\Events\Action;
use App\Events\ActionUser;
use App\Events\Hello;
use App\Http\Controllers\Controller;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Posts::all()->toArray();
        return array_reverse($posts);
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();
        $imageName = NULL;
        if ($image = $request->file('file')) {
            $destinationPath = 'img/';
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
            $input['image'] = $imageName;
        }

        Posts::create($input);

        return response()->json(['success'=> 'Post created successfully']);

    }

    public function senddata(Request $request)
    {
        //broadcast(new Hello($request->partida_id));
        broadcast(new ActionUser($request->partida_id, $request->user_id, $request->action, Auth::user()));
        //broadcast(new Action($request->partida_id));
        return response()->json(['success'=> 'Sended']);
    }

    public function edit($id)
    {
        $post = Posts::find($id);
        return response()->json($post);
    }

    public function update($id, Request $request)
    {
        $post = Posts::find($id);
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $input = $request->all();
        $imageName = NULL;
        if ($image = $request->file('file')) {
            $destinationPath = 'img/';
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
            $input['image'] = $imageName;
            unlink('img/'.$post->image);
        }

        $post->update($input);

        return response()->json(['success'=> 'Post update successfully']);
    }

    public function delete($id)
    {
        $post = Posts::find($id);
        $post->delete();
        unlink('img/'.$post->image);
        return response()->json(['success'=> 'Post deleted successfully']);

    }
}
