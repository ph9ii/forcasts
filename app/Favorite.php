<?php

namespace App;

use App\Traits\RecordActivityTrait;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
	use RecordActivityTrait;

    protected $guarded = [];

    public function favorited()
    {
    	return $this->morphTo();
    }
}
