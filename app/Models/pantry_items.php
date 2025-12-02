<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pantry_items extends Model
{
    protected $table = 'pantry-items';

    public function unit()
    {
        return $this->belongsTo(Units::class, 'unit_id');
    }
}
