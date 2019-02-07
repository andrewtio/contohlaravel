@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <h1>Halaman Notifikasi</h1>
        <ul class="list-group">
          @foreach ($notifications as $notif)
            <li class="list-group-item">
              <a href="/quotes/{{$notif->quote->slug}}">
                 {{$notif->subject . ' di kutipan ' . $notif->quote->title}}
               </a></li>
          @endforeach
        </ul>
    </div>

    @php
      $notif_model::where('user_id', $user->id)->where('seen', 0)->update(['seen' => 1]);
    @endphp

  </div>
</div>
@endsection
