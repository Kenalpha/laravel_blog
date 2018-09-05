@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if(count($errors) > 0)
              @foreach($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                    {{ $error }}
                </div>
              @endforeach
            @endif
            
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header bg-primary text-white">Dashboard
                <form method="POST" action='{{ url("/search") }}'>
                    @csrf  
                      <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search for...">
                        <span>
                          <button type="submit" class="btn btn-default">
                            Go!
                          </button>
                        </span>
                      </div>
                  </form>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3">
                      @if(!empty($profile))
                        <img class="avatar" src='{{ $profile->profile_pic }}' alt='Profile Picture'>
                      @else
                        <img class="avatar" src='{{ url("/images/avatar.svg") }}' alt='Profile Picture'>
                      @endif

                      @if(!empty($profile))
                        <p class="lead">{{ $profile->name }}</p>
                      @else
                        
                      @endif

                      @if(!empty($profile))
                        <p class="lead">{{ $profile->designation }}</p>
                      @else
                        
                      @endif
                      
                      
                      
                    </div>
                    <div class="col-md-9 text-center">
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
                              @if(Auth::id() == 1)
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
