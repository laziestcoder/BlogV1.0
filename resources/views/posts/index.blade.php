@extends('layouts.app')

@section('content')
<div >
        <h1>{{$title}}</h1>
</div>
    @if(count($posts) > 0)
        @foreach($posts as $post)
            <div class="well">
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <img style="width:50%; height: 50%" src="/storage/cover_images/{{$post->cover_image}}" alt="{{$post->title}}">
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <h3><a href="/posts/{{$post->id}}">{{$post->title}}</a></h3>
                        <small>Written on {{$post->created_at}} by {{$post->user->name}}</small>
                    </div>
                </div>
            </div>
        @endforeach
        {{$posts->links()}}    
    @else
        <p>No posts found</p>
    @endif
@endsection