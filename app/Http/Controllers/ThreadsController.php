<?php

namespace App\Http\Controllers;

use App\User;
use App\Thread;
use App\Channel;
use Illuminate\Http\Request;
use App\Filters\ThreadFilters;

class ThreadsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param App\Channel $channel
     * @param ThreadFilters $filters
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if(request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', compact('threads'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string',
            'channel_id' => 'required|exists:channels,id',
            'body' => 'required|string',
        ];

        $this->validate($request, $rules);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => $request->channel_id,
            'title' => $request->title,
            'body' => $request->body
        ]);

        return redirect("/threads/{$thread->channel->slug}/{$thread->id}")
            ->with('flash','Thread created');
    }

    /**
     * Display the specified resource.
     *
     * @param  $channel
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread)
    {
        return view('threads.show', [
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(10)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @param  $channel
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        // $thread->replies()->delete(); // We will use event here

        $this->authorize('delete', $thread);

        $thread->delete();

        if(request()->wantsJson()) {
            return response([], 200);
        }

        return redirect('/threads')->with('flash', 'Thread deleted');
    }

    /**
     * Display a listing of the resource.
     *
     * @param $channel
     * @param $filters
     * @return \Illuminate\Http\Response
     */
    protected function getThreads($channel, $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        // dd($threads->toSql());

        return $threads = $threads->get();
    }
}
