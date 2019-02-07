@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <h1>{{$user->name}}</h1>
        <ul class="list-group">
          @foreach ($user->quotes as $quote)
            <li class="list-group-item"> <a href="/quotes/{{$quote->slug}}"> {{$quote->title}}</a></li>
          @endforeach
        </ul>
    </div>
  </div>
</div>
@endsection
