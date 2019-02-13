<?php

namespace App;

use App\User;
use App\Favorite;
use App\Traits\FavTrait;
use App\Traits\RecordActivityTrait;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use FavTrait, RecordActivityTrait;

	protected $guarded = [];

    protected $appends = ['favoritesCount', 'isFavortied'];

    protected $with = ['owner', 'favorites'];

    protected $hidden = [
        'email'
    ];
	
    public function owner()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function favorites()
    {
    	return $this->morphMany(Favorite::class, 'favorited');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }
}
