<?php

namespace App\Http\Controllers;
use App\ImageCourse;
use App\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class imageController extends Controller
{
    public function create(Request $request){
        $rules=[
            "image"=>"required|url",
            "course_id"=>"required|integer"
        ];
        $data =$request->all();

        $validator =Validator::make($data,$rules);
        if($validator->fails()){
            return response()->json(["status"=>"error","massage"=>$validator->errors()],400);
        }
        $courseId=$request->input("course_id");
        $course =Course::find($courseId);
        if(!$course){
            return response()->json([
                "status"=>"error",
                "massage"=>"course not found"
            ],404);
        }
        $imageCourse =ImageCourse::create($data);
        return response()->json(["status"=>"succes","data"=>$imageCourse]);

    }
     public function destroy($id){
    
        $course =Course::find($id);
        if(!$course){
            return response()->json([
                "status"=>"error",
                "massage"=>"course not found"
            ],404);
        }
        $course->delete();
        return response()->json(["status"=>"succes","massage"=>"image deleted"]);

    }
}
