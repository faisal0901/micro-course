<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    //
    protected $table="chapters";
    protected $fillable=["name","course_id"];
    public function lesson(){
        return $this->hasMany("App\Lessons")->orderBy("id",'ASC');
    }
}
