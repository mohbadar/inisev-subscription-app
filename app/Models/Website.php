<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class,'website_id', 'id');
    }

    public function posts()
    {
        return $this->hasMany(Posts::class, 'website_id', 'id');
    }

}
