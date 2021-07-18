<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
   protected $table="reviews";
   protected $fillable=["user_id","course_id","rating","note"];
   public function course(){
       return $this->belongsTo('App\Course');
   }
}
