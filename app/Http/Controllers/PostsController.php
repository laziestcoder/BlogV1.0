<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use DB;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index','show'] ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $posts = Post::all ();
        // $posts =  Post::orderBy('title','Post Two')->get();
        // $posts = DB::select('SELECT * FROM posts');
        // $posts =  Post::orderBy('id','desc')->take(1)->get();
        // $posts =  Post::orderBy('id','desc')->get();
        $title = 'Posts';
        $posts =  Post::orderBy('id','desc')->paginate(25);
        $data = array(
            'title' => $title,
            'posts' => $posts
        );
        return view('posts.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array(
            'title' => 'Create Post',
            'posts' => ''
        );
        return view('posts.create')->with($data);    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        //Handle File Upload

         if($request->hasFile('cover_image'))
        {
            //Get Filename with extension
            $filenameWithExt = $request -> file('cover_image')->getClientOriginalName();
            
            // Get just file name
            $filename = pathinfo($filenameWithExt,PATHINFO_FILENAME);

            // Get just file extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();

            // File name to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            
            //uload image
            $path = $request->file('cover_image')->storeAs('public/cover_images',$fileNameToStore);

        }
        else 
        {
            $fileNameToStore ='noimage.jpeg';
        } 

        //Create Post
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        //$post->cover_image = 'noimage.jpeg';
        $post->save();
        return redirect('/posts')->with('success','Post Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post',$post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        
        //Check for correct user

        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error','Unauthorized Access Denied!');

        }

        // Edit post
        
        $data = array(
            'title' => 'Edit Post',
            'post' => $post
        );
        return view('posts.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
       /*  $data = array(
            'title' => $title,
            'posts' => $posts
        );
        return view('posts.update')->with($data); */
        
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);
        //Update Post
        //$post = new Post; //Handle File Upload

         if($request->hasFile('cover_image'))
         {
             //Get Filename with extension
             $filenameWithExt = $request -> file('cover_image')->getClientOriginalName();
             
             // Get just file name
             $filename = pathinfo($filenameWithExt,PATHINFO_FILENAME);
 
             // Get just file extension
             $extension = $request->file('cover_image')->getClientOriginalExtension();
 
             // File name to store
             $fileNameToStore = $filename.'_'.time().'.'.$extension;
             
             //uload image
             $path = $request->file('cover_image')->storeAs('public/cover_images',$fileNameToStore);
 
         }

        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($request->hasFile('cover_image'))
         {  
             $post->cover_image = $fileNameToStore;
         }
        $post->save();
        return redirect('/posts/'.$id)->with('success','Post Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $post = Post::find($id);

        //Check for correct user

        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error','Unauthorized Access Denied!');

        }
        if($post->cover_image != 'noimage.jpeg' ){
            //Delete Image From Windows Directory
            Storage::delete('public/cover_images/'.$post->cover_image);
        }

        $post->delete();
        return redirect('/posts')->with('success','Post Removed Successfully!');
    }
}
