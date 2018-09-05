@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">View Post</div>

                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 text-center">
                      <ul class="list-group">
                        @if(count($categories) > 0)
                          @foreach($categories->all() as $category)
                            <li class="list-group-item"><a href='{{ url("/category/{$category->id}") }}'>{{ $category->category }}</a></li>
                          @endforeach
                        @else
                          <p>No Categories Found</p>
                        @endif
                      </ul>
                    </div>
                    <div class="col-md-8 text-center">
                      @if(count($posts) > 0)
                        @foreach($posts->all() as $post)
                          <h4>{{ $post->post_title }}</h4>
                          <img class="img-fluid"  src='{{ $post->post_image }}' alt='Post Picture'>
                          <P>{{ substr($post->post_body, 0, 150) }}</P>
                          <ul class="nav nav-pills justify-content-center">
                            <li>
                              <a href='{{ url("/view/{$post->id}") }}'>
                                <span class="fa fa-eye">View</span>
                              </a>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              @if(Auth::id() == 1)
                                <a href='{{ url("/edit/{$post->id}") }}'>
                                  <span class="fa fa-pencil-square-o">Edit</span>
                                </a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href='{{ url("/delete/{$post->id}") }}'>
                                  <span class="fa fa-trash" style="color:#ff4d4d;">Delete</span>
                                </a>
                              @endif
                            </li>
                          </ul>
                          <cite style="float: left;">Posted on: {{ date('M j, Y H:i', strtotime($post->updated_at))}}</cite>
                          &nbsp;&nbsp;
                          <hr>
                        @endforeach
                      @else
                        <h3>No Posts Available</h3>
                      @endif
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
