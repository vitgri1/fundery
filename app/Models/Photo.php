<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManagerStatic as Image;

class Photo extends Model
{
    use HasFactory;
    protected $fillable = ['photo', 'idea_id'];
    public $timestamps = false;

    public static function add(UploadedFile $gallery, int $idea_id = 0)
    {
        $name = $gallery->getClientOriginalName();
        $name = rand(100000000, 999999999) . '-' . $name;
        $path = public_path() . '/ideas-photo/';
        $gallery->move($path, $name);
        if($idea_id != 0){
            self::create([
                'idea_id' => $idea_id,
                'photo' => $name
            ]);
        }
        $img = Image::make($path . $name);
        $img->resize(200, 200);
        $img->save($path . 't_' . $name, 90);
        return $name;
    }

    public function deletePhoto()
    {
        $photo = public_path() . '/ideas-photo/' . $this->photo;
        unlink($photo);
        $photo = public_path() . '/ideas-photo/t_' . $this->photo;
        unlink($photo);
        $this->delete();
    }
}
