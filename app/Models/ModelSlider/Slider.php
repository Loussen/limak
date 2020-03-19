<?php
namespace App\ModelSlider;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use Translatable;
    protected $fillable = ['file'];
    public $translatedAttributes = ['name'];

    public function translates() {
        return $this->hasMany('App\ModelSlider\SliderTranslate', 'slider_id');
    }
}