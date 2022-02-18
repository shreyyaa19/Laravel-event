<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;

use Str;


class Admin extends Model
{
    /**
     * The validation rules for the model.
     *
     * @var array $rules
     */
    protected $rules = [
        'name'           => ['required'],
        'email'          => ['required', 'email'],
        
    ];

    

    /**
     * The account associated with the organiser
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class);
    }

    /**
     * The events associated with the organizer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(\App\Models\Event::class);
    }

    /**
     * The attendees associated with the organizer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function Eventattendies()
    {
        return $this->hasManyThrough(\App\Models\EventAttendies::class, \App\Models\Event::class);
    }

   

}
