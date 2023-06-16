<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Reservations extends Model
{
    use HasFactory;
    protected $table = 'reservations';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function service()
    {
        return $this->belongsTo(Services::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointments::class);
    }
}
