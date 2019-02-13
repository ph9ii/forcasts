<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Channel;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     * @param  $channel
     * @param  \App\Thread $thread
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Channel $channel, Thread $thread, Request $request)
    {
        $this->validate($request, [
            'body' => 'required|string'
        ]);

        $reply = $thread->addReply([
        	'user_id' => auth()->id(),
        	'body' => $request->body
        ]);

        if(request()->expectsJson()) {
            return $reply->load('owner');
        }

        return redirect()
            ->back()
            ->with('flash','Reply created');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update(request(['body']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('delete', $reply);

        $reply->delete();

        if(request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }
        
        return redirect()
            ->back()
            ->with('flash', 'Reply deleted');
    }
}
