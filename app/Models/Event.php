<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Iluseluminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description','date','place','status'
    ];
    public function eventattendies()
    {
        return $this->hasMany(\App\Models\EventAttendies::class);
    }
    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class);
    }
    public function ticketstatus()
    {
        return $this->hasMany(\App\Models\TicketStatus::class);
    }
    public function admin()
    {
        return $this->belongsTo(\App\Models\Admin::class);
    }
}