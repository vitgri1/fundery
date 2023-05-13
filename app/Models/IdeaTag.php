<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeaTag extends Model
{
    use HasFactory;
    protected $fillable = ['tag_id', 'idea_id'];
    public $timestamps = false;
}
