<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Tag;
use App\Models\User;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tags = Tag::all();

        $search_q = urlencode($request->input('search'));

        if(!empty($search_q))
          $quotes = Quote::with('tags')->where('title', 'like', '%'.$search_q.'%')->get();
        else
          // $quotes = Quote::all(); // ini yang sebelumnya
          $quotes = Quote::with('tags')->get();
        return view('quotes.index', compact('quotes', 'tags'));
    }

    public function filter($tag)
    {
        $tags = Tag::all();

        $quotes = Quote::with('tags')->whereHas('tags', function($query) use($tag){
                    $query->where('name', $tag);
                  })->get();

        return view('quotes.index', compact('quotes', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all();
        return view('quotes.create', compact('tags'));
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
          'title' => 'required|min:3',
          'subject' => 'required|min:5',
        ]);

        $request->tags = array_unique(array_diff($request->tags, [0]));
        if(empty($request->tags))
          return redirect('quotes/create')->withInput($request->input())->with('tag_error', 'tag nggak boleh kosong bro');

        $slug = str_slug($request->title, '-');
        // check if already exists

        //cek slug nggak kembar
        if (Quote::where('slug', $slug)->first() != null)
          $slug = $slug . '-' .time();

        $quote = Quote::create([
          'title' => $request->title,
          'slug' => $slug,
          'subject' => $request->subject,
          'user_id' => Auth::user()->id
        ]);

        $quote->tags()->attach($request->tags);

        return redirect('quotes')->with('msg', 'kutipan berhasil disubmit');
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $quote = Quote::with('comments.user')->where('slug', $slug)->first();

        if (empty($quote)) {
          abort(404);
        }

        return view('quotes.single', compact('quote'));
    }

    public function random()
    {
        $quote = Quote::inRandomOrder()->first();
        return view('quotes.single', compact('quote'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $quote = Quote::findOrFail($id);
      $tags = Tag::all();
      return view('quotes.edit', compact('quote', 'tags'));
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
      $this->validate($request, [
        'title' => 'required|min:3',
        'subject' => 'required|min:5',
      ]);

      $request->tags = array_diff($request->tags, [0]);
      if(empty($request->tags))
        return redirect('quotes/create')->withInput($request->input())->with('tag_error', 'tag nggak boleh kosong bro');

      $quote = Quote::findOrFail($id);
      if($quote->isOwner()) {
          $quote->update([
              'title' => $request->title,
              'subject' => $request->subject,
          ]);

          $quote->tags()->sync($request->tags);

      }
      else abort(403);

      return redirect('quotes')->with('msg', 'kutipan berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
     {
         $quote = Quote::findOrFail($id);
         if($quote->isOwner())
             $quote->delete();
         else abort(403);

         return redirect('quotes')->with('msg', 'kutipan berhasil dihapus');
     }
}
