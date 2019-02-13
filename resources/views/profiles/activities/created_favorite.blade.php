@component('profiles.activities.activity')
    @slot('heading')
        <a href="">{{ $profileUser->name }}</a> favorited a <a href="{{ $record->subject->favorited->path() }}">reply</a>
    @endslot
    @slot('body')
        {{ $record->subject->favorited->body }}
    @endslot
@endcomponent