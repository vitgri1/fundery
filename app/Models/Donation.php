<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'idea_id', 'donator_id'];
    public $timestamps = false;

    public function ideaFor() 
    {
        return $this->belongsTo(Idea::class, 'idea_id', 'id');
    }

    public function donator() 
    {
        return User::where('id', $this->donator_id)->first()->name;
    }
}
