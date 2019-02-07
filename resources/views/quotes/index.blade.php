@extends('layouts.app')

@section('content')
<div class="container">

  @if (session('msg'))
    <div class="alert alert-success">
      <p> {{ session('msg') }}</p>
    </div>
  @endif

  <div class="row">



      <form class="navbar-form navbar-left" action="/quotes" method="get">
        <div class="from-group">
          <input type="text" class="form-control" placeholder="Search" name="search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>

      Filter Tag:
      @foreach ($tags as $tag)
        <a href='/quotes/filter/{{$tag->name}}'> / {{ $tag->name }} </a>
      @endforeach

    <div class="col-md-5 col-md-offset-4">
      <a href='quotes' class="btn btn-primary">
        All
      </a>
      <a href='quotes/random' class="btn btn-primary">
        Random
      </a>
      <a href='quotes/create' class="btn btn-primary">
        Create
      </a>
    </div>
  </div>

  <br>

  <div class="row">
    @foreach ($quotes as $quote)
      <div class="col-md-4">
        <div class="card">
          <div class="caption">{{ $quote->title }}</div>
          <span>Tag:
            @foreach ($quote->tags as $tag)
              <span> {{ $tag->name }} </span>
            @endforeach
          </span>
          <p>
            <a href="/quotes/{{$quote->slug}}" class="btn btn-primary">Lihat Kutipan</a>
          </p>

        </div>
        <br>
      </div>
    @endforeach
  </div>
</div>
@endsection
