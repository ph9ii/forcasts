<reply :attributes="{{ $reply }}" inline-template v-cloak>
	<div id="reply-{{ $reply->id }}" class="panel panel-default">
		<div class="panel-heading">
			<div class="level">
				<h5 class="flex">
					<a href="{{ route('profiles.show', ['user' => $reply->owner->name]) }}">
					{{ $reply->owner->name }}</a> said {{ $reply->created_at->diffForHumans() }}
				</h5>
				@if(Auth()->check())
					<div>
						<favorite :reply="{{ $reply }}"></favorite>
					</div>
				@endif
			</div>
		</div>
		<div class="panel-body">
			<div v-if="editing">
				<div class="form-group">
                  <textarea class="form-control" id="body" name="body" placeholder="Write a reply..."cols="5" rows="5" v-model="body"></textarea>
                </div>
                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
			</div>
			<div v-else v-text="body"></div>
		</div>

		@can('delete', $reply)
			<div class="panel-footer level">
				<button class="btn btn-xs mr-1" @click="editing = true">Edit
				</button>
				<button class="btn btn-danger btn-xs" @click="destroy">Delete
				</button>
			</div>
		@endcan                    
	</div>
</reply>