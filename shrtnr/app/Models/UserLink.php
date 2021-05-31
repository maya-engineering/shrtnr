<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_link', 
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function click(){
        return $this->hasOne(click::class);
    }

}
