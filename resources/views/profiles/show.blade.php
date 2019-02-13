@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
        	<div class="page-header">
        		<h1>
        			{{ $profileUser->name }}
        			<small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
        		</h1>
        	</div>
                @forelse ($activities as $date => $activity)
                    <h3 class="page-header">{{ $date }}</h3>
                    @foreach($activity as $record)
                        <div class="panel panel-default">
                        @if(view()->exists("profiles.activities.{$record->type}"))
                            @include("profiles.activities.{$record->type}")
                        @endif
                        </div>
                    @endforeach
                    @empty
                    <p>There is no activities for this user yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
	
@endsection