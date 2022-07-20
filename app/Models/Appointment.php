<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['pet_id', 'date', 'hour'];

    public function pet() {
        return $this->belongsTo(Pet::class);
    }

}
