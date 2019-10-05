<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $timestamps = true;
    public $fillable = ['title','body','category_id'];

    public function categories()
    {
        return $this->belongsTo('App\Category')->with('childs');
    }

}
