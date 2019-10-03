<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = true;
    public $fillable = ['title','parent_id'];


    public function childs() {

     return $this->hasMany('App\Category','parent_id','id') ;

            }

  public function articles()
    {
        return $this->hasMany('App\Article','category_id','id');
    }
}
