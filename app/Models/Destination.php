<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $table = 'destinations';
    public $timestamps = false;
    protected $guarded = [];

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
