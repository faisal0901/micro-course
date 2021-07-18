<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    protected $table="mentors";
    protected $cast=[
    "created_at"=>"datetime:y-m-d H:m:s",
    "updated_at"=>"datetime:y-m-d H:m:s",
    ];
    protected $fillable=["name","profile","email","profession"];

}
