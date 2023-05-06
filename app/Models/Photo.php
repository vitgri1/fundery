<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Photo extends Model
{
    use HasFactory;
    protected $fillable = ['photo', 'idea_id'];
    public $timestamps = false;

    public static function add(UploadedFile $gallery, int $idea_id)
    {
        $name = $gallery->getClientOriginalName();
        $name = rand(100000000, 999999999) . '-' . $name;
        $path = public_path() . '/ideas-photo/';
        $gallery->move($path, $name);
        self::create([
            'idea_id' => $idea_id,
            'photo' => $name
        ]);
    }

    public function deletePhoto()
    {
        $photo = public_path() . '/ideas-photo/' . $this->photo;
        unlink($photo);
        $this->delete();
    }
}
