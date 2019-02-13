<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait FavTrait
{
    public static function bootFavTrait()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

	public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];
        
        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create(['user_id' => auth()->id()]);
        }    
    }

    public function unFavorite()
    {
        $attributes = ['user_id' => auth()->id()];
        
        // Use higher order collections - kind of syntactic sugar syntax
        
        $this->favorites()->where($attributes)->get()->each->delete();

        // $this->favorites()->where($attributes)->get()->each(function ($favorite) {
        //     $favorite->delete();
        // });    
    }

    public function isFavortied()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavortiedAttribute()
    {
        return $this->isFavortied();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}