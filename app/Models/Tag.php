<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['title'];
    public $timestamps = false;
    const SORT = [
        'title_asc' => 'By title A-Z',
        'title_desc' => 'By title Z-A',
    ];

    const FILTER = [
        'default' => 'Show all',

    ];

    const PER = [
        '5' => '5',
        '10' => '10',
        '15' => '15',
        '20' => '20',
        '50' => '50',
        '100' => '100',
    ];
}
