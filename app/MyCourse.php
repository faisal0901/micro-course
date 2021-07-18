<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyCourse extends Model
{
      protected $table="my_course";
    protected $fillable=["course_id","user_id"];
    public function course(){
        return $this->belongsTo("App\Course");
    }
}
