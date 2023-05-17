<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Idea extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'type', 'funds', 'created_at', 'tags', 'hearts', 'photo', 'likes'];
    public $timestamps = false;
    protected $casts = [
        'tag_ids' => 'array',
        'hearts' => 'array',
    ];
    
    const SORT = [
        'title_asc' => 'By title A-Z',
        'title_desc' => 'By title Z-A',
        'likes_desc' => 'By most likes',
        'likes_asc' => 'By lowest likes',
    ];

    const FILTER = [
        'default' => 'Show all',
        'not' => 'not approved',
        'yes' => 'approved',
        'end' => 'finished ',
    ];

    const PER = [
        '5' => '5',
        '10' => '10',
        '15' => '15',
        '20' => '20',
        '50' => '50',
        '100' => '100',
    ];

    public function likes()
    {
        return count($this->hearts);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function gallery()
    {
        return $this->hasMany(Photo::class, 'idea_id', 'id');
    }

    public function ideaTag()
    {
        return $this->hasMany(IdeaTag::class);
    }

    public function deletePhoto()
    {
        if ($this->photo) {
            $photo = public_path() . '/ideas-photo/' . $this->photo;
            unlink($photo);
            $photo = public_path() . '/ideas-photo/t_' . $this->photo;
            unlink($photo);
        }
        $this->update([
            'photo' => null,
        ]);
    }

    public function totalDonated() : float
    {
        return Donation::where('idea_id', '=', $this->id)->sum('amount');
    }
}
