@extends('layouts.app')

@section('content')

  @if (session('msg'))
    <div class="alert alert-success">
      <p> {{ session('msg') }}</p>
    </div>
  @endif

<div class="container">
  <div class="jumbotron">
    <h1>{{$quote->title}}</h1>
    <p> {{$quote->subject}}</p>
    <p>Ditulis oleh: <a href="/profile/{{$quote->user->id}}"> {{$quote->user->name}} </a></p>

    <a href="/quotes" class="btn btn-primary btn-lg">Balik ke Daftar</a>

    @component('layouts/likes',
      [ 'content' => $quote, 'model_id' => 1 ])
    @endcomponent

    @if($quote->isOwner())
        <p><a href="/quotes/{{$quote->id}}/edit" class="btn btn-primary btn-lg"> Edit</a></p>
        <form method="POST" action="/quotes/{{$quote->id}}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-danger">Hapus</button>
        </form>
    @endif
  </div>

  @foreach ($quote->comments as $comment)
    <div class="row">
      <div class="col-md-4">
        <p>{{$comment->subject}}</p>
        <p>Ditulis oleh: <a href="/profile/{{$comment->user->id}}"> {{$comment->user->name}} </a></p>
      </div>

      @if ($comment->isOwner())
        <div class="col-md-2">
          <a href="/quotes-comment/{{$comment->id}}/edit" class="btn btn-primary btn-lg"> Edit</a>
        </div>

        <div class="col-md-2">
          <form method="POST" action="/quotes-comment/{{$comment->id}}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-danger">Hapus</button>
          </form>
        </div>

      @endif

      <div class="col-md-2">
        @component('layouts/likes',
          [ 'content' => $comment, 'model_id' => 2 ])
        @endcomponent
      </div>

      <hr>
    </div>
    <hr>
  @endforeach


  @if (count($errors) > 0)
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{$error}}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="/quotes-comment/{{$quote->id}}">
      <div class="form-group">
          <label for="subject">Isi Komentar</label>
          <textarea class="form-control" name="subject" rows="8" cols="80">{{old('subject')}}</textarea>
      </div>
      {{ csrf_field() }}
      <button type="submit" class="btn btn-default btn-block">Submit Komentar</button>
  </form>

  <script src="{{ asset('js/quote.js')}}" charset="utf-8"></script>

</div>
@endsection
