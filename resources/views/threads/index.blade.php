@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                @forelse($threads as $thread)     
                <div class="panel-heading">
                    <div class="level">
                        <h4 class="flex">
                            <a href="{{ $thread->path() }}">
                                {{ $thread->title }}
                            </a>
                        </h4>
                        <strong>
                            <a href="{{ $thread->path() }}">
                                {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                            </a>
                        </strong>
                    </div>
                </div>

                <div class="panel-body">                       
                    <div class="body">
                        {{ $thread->body }}
                    </div>
                </div>
                <hr>
                @empty
                    <p class="center">
                    There are no relevant results at this time.
                    </p>
                @endforelse        
            </div>
        </div>
    </div>
</div>
@endsection
