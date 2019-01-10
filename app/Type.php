<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = ['name', 'description', ];
    public function tickets()
    {
        return $this->hasMany('App\Ticket');
    }
}
