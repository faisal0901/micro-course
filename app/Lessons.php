<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lessons extends Model
{
    protected $table="lessons";
    protected $fillable=[
        "name","video","chapter_id"];
}
