<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class recipes extends Model
{
    protected $fillable = ['title', 'instruction', 'tags' , 'user_id'];
}
