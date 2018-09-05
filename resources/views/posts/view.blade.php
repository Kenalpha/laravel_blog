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
                          <img class="img-fluid" src='{{ $post->post_image }}' alt='Post Picture'>
                          <P>{{ $post->post_body }}</P>
                          <ul class="nav nav-pills justify-content-center">
                            <li>
                              <a href='{{ url("/like/{$post->id}") }}'>
                                <span class="fa fa-thumbs-up">Like({{ $likes }})</span>
                              </a>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <a href='{{ url("/dislike/{$post->id}") }}'>
                                <span class="fa fa-thumbs-down">Dislike({{ $dislikes }})</span>
                              </a>
                            </li>
                          </ul>
                          <br><hr>
                          <h6 style="color: #2980b9;">Comment Section</h6>
                          <form method="POST" action='{{ url("comment/{$post->id}") }}'>
                              @csrf
                              <div class="form-group row">
                                  <div class="col-md-12">
                                      <textarea id="textarea" rows="7" class="form-control{{ $errors->has('comment') ? ' is-invalid' : '' }}" name="comment" value="{{ old('comment') }}" required></textarea>
                                      </textarea>
                                      @if ($errors->has('comment'))
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $errors->first('comment') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                              </div>
                              <div class="form-group">
                                  <div class="col-md-12">
                                      <button type="submit" class="btn btn-primary btn-block">
                                          Comment
                                      </button>
                                  </div>
                              </div>
                          </form>
                          @if(count($comments) > 0)
                            @foreach($comments->all() as $comment)
                                  <p>{{$comment->comment}}</p>
                                  <p>By: {{$comment->name}}</p><hr>
                            @endforeach
                          @endif
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
