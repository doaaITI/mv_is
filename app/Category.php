<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = true;
    public $fillable = ['title','parent_id'];

    //with recursion we can get all childs

    public function childs() {

     return $this->hasMany('App\Category','parent_id','id')->with('childs') ;

            }



  public function articles()
    {
        return $this->hasMany('App\Article','category_id','id');
    }


    public static function getAllArticles($categoryId, $articles = null)
    {

      if ($articles === null) {
         $articles = collect();
      }
      $category = Category::find($categoryId);
      $articles = $articles->merge($category->articles);

      $category->childs->each(function($child ) {
          $articles = self::getAllArticles($child->id,$articles);
      });

      return $articles;
    }
}
