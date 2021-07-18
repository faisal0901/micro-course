<?php

namespace App\Http\Controllers;
use App\Course;
use App\MyCourse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MyCourseController extends Controller
{
    public function index(Request $request){
        $myCourse=MyCourse::query();
        $userId=$request->query("user_id");

        $myCourse->when($userId,function($q)use($userId){
            return $q->where("user_id","=",$userId);
        });
        return response()->json(["status"=>"succes","data"=>$myCourse->get()]);
    }
    public function create(Request $request){
        $rules=["course_id"=>"required|integer","user_id"=>"required|integer"];
        $data=$request->all();
        $validator =Validator::make($data,$rules);
        if($validator->fails()){
            return response()->json(["status"=>"error","message"=>$validator->errors()],400);
        }
        $courseId =$request->input("course_id");
        $course=Course::find($courseId);
        if(!$course){
            return response()->json(["status"=>"error","message"=>"course not found"],404);
        }
        $userId=$request->input("user_id");
        $user=getUser($userId);

        if($user["status"]==="error"){
            return response()->json(["status"=>$user["status"],"message"=>$user["message"]],$user["http_code"]);
        }
        $isExistCourse=MyCourse::where("course_id","=",$courseId)->where("user_id","=",$userId)->exists();
        if($isExistCourse){
            return response()->json(["status"=>"error","message"=>"user already exist in this course"],400);
        }
        if($course->type==='premium'){
            if($course->price===0){
                return response()->json(["status"=>"error","massage"=>"price can not be 0"]);
            }
           $order= postOrder([
               "user"=>$user["data"],
               'course'=>$course->toArray(),
           ]);
           if($order['status']==="error"){
               return response()->json(["status"=>$order["status"],"message"=>$order["message"]],$order["http_code"]);
           }
           return response()->json(["status"=>$order["status"],'data'=>$order["data"]]);
        }else{

            $myCourse=MyCourse::create($data);
            return response()->json(["status"=>"success","message"=>$myCourse]);
        }
    }
    public function createPremiumAcces(Request $request){
        $data=$request->all();
        $myCourse=MyCourse::create($data);
        return response()->json(['status'=>'succes',"data"=>$myCourse]);
    }

}
