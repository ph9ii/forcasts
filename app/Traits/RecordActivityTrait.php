<?php

namespace App\Traits;

use App\Activity;
use Illuminate\Database\Eloquent\Model;

trait RecordActivityTrait
{
	protected static function bootRecordActivityTrait()
    {
        if (auth()->guest()) return;

        foreach(static::getActivitesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    protected function recordActivity($event)
    {
        return $this->activity()->create([
            'user_id' => $this->user_id,
            'type' => $this->getActivityType($event)
        ]);
    }

    protected static function getActivitesToRecord()
    {
        return ['created'];
    }

    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return $event . '_' . $type;
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }
}