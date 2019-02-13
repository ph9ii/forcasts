@component('profiles.activities.activity')
    @slot('heading')
        <a href="">{{ $profileUser->name }}</a> published thread <a href="{{ $record->subject->path() }}">"{{ $record->subject->title }}"</a>
    @endslot
    @slot('body')
        {{ $record->subject->body }}
    @endslot
@endcomponent