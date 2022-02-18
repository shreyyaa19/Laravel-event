<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAttendies extends Model
{
    use HasFactory;

    protected $table = 'event_attendees';
    
    protected $fillable = [
        'title', 'name','email','phone','description'
    ];
    
    
    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class);
    }
    /**
     * The organizer associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(\App\Models\Admin::class);
    }

}