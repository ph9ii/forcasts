@extends('layouts.app')

@section('content')
<thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="level">
                        <span class="flex">
                            {{ $thread->title }}  posted by 
                            <a href="{{ route('profiles.show', ['user' =>   $thread->creator->name]) }}">
                                {{ $thread->creator->name }}
                            </a>
                        </span>

                        @can('delete', $thread)
                        <form method="POST" action="{{ route('threads.destroy', ['channel' => $thread->channel->slug, 'thread' => $thread->id]) }}">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="submit" class="btn btn-link">Delete</button>
                        </form>
                        @endcan
                    </div>
                </div>

                <div class="panel-body">
                    {{ $thread->body }}
                </div>
            </div>

            <replies :data="{{ $thread->replies }}" 
                @added="repliesCount++"
                @removed="repliesCount--">
            </replies>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p>This thread was published {{ $thread->created_at->diffForHumans() }} by <a href="{{ route('profiles.show', $thread->creator) }}">{{ $thread->creator->name }}</a>, and have <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</thread-view>
@endsection
