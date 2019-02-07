<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use LikesTrait;

    protected $fillable = [
        'title', 'slug', 'subject','user_id'
    ];

    public function user()
    {
      return $this->belongsTo('App\Models\User');
    }

    public function comments()
    {
      return $this->hasMany('App\Models\QuoteComment');
    }

    public function tags()
    {
      return $this->belongsToMany('App\Models\Tag');
    }

    public function isOwner()
    {
      if(Auth::guest())
      return false;

      return Auth::user()->id == $this->user->id;
    }
}
