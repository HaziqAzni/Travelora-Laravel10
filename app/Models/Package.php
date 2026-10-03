<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';
    public $timestamps = false;
    protected $guarded = [];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
