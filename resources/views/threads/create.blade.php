@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create New Thread</div>
                    <div class="panel-body">
                        <form method="post" action="{{ route('threads.store') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                            <label for="channel">Channel select</label>
                                <select class="form-control" id="channel_id" 
                                    name="channel_id" required>
                                    <option value="">Choose a channel</option>
                                  @foreach($channels as $channel)
                                    <option value="{{ $channel->id }}"
                                        {{ old('channel_id') == $channel->id ? 'selected' : ''}}>
                                        {{ $channel->name }}
                                    </option>
                                  @endforeach
                                </select>
                                @if ($errors->has('channel_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('channel_id') }}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="title">Body</label>
                                <textarea class="form-control" id="body" name="body" placeholder="Write something..." rows="5" required>{{ old('body') }}
                                </textarea>
                                @if ($errors->has('body'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                                @endif
                              <br>
                              <button type="submit" class="btn btn-primary">
                                Publish
                              </button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
