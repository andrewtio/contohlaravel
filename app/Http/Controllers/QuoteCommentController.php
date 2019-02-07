<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Quote;
use App\Models\QuoteComment;
use App\Models\Notification;
use Illuminate\Http\Request;

class QuoteCommentController extends Controller
{
  public function store(Request $request, $id)
  {
      $this->validate($request, [
          'subject' => 'required|min:5',
      ]);

      $quote = Quote::findOrFail($id);

      $quoteComment = QuoteComment::create([
          'subject' => $request->subject,
          'quote_id' => $id,
          'user_id' => Auth::user()->id
      ]);

      if($quote->user->id != Auth::user()->id){
        Notification::create([
          'user_id' => $quote->user->id,
          'quote_id' => $id,
          'subject' => 'ada komentar dari '. Auth::user()->name,
        ]);
      }

        return redirect('/quotes/'.$quote->slug)->with('msg', 'komentar berhasil disubmit');
    }

    public function edit($id)
    {
        $comment = QuoteComment::findOrFail($id);
        return view('quote-comments.edit', compact('comment'));
    }

    public function update(Request $request, $id)
    {
        $comment = QuoteComment::findOrFail($id);
        if($comment->isOwner())
            $comment->update([
                'subject' => $request->subject,
            ]);
        else abort(403);

        return redirect('/quotes/'. $comment->quote->slug)->with('msg', 'komentar berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
     {
         $comment = QuoteComment::findOrFail($id);
         if($comment->isOwner())
             $comment->delete();
         else abort(403);

         return redirect('/quotes/'.$comment->quote->slug)->with('msg', 'komentar berhasil dihapus');
     }
}
