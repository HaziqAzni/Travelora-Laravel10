<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $table = 'newsletter_subscribers';
    public $timestamps = false;
    protected $guarded = [];
}
