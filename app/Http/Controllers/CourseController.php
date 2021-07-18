<?php

namespace App\Http\Controllers;
use App\Course;
use App\Mentor;
use App\Review;
use App\MyCourse;
use App\Chapter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    public function index(Request $request){
      $courses=Course::query();
      $q=$request->query("q");
      $status=$request->query("status");
      $courses->when($q,function($query)use($q){
          return $query->whereRaw("name Like '%".strtolower($q)."%'");
      });
      $courses->when($status,function($query)use($status){
          return $query->where("status","=",$status);
      });
      return response()->json([
          "status"=>"succes",
          "data"=>$courses->paginate(10)
      ]);
    }
    public function show($id){
            $course=Course::with('chapters.lesson')->with("mentor")->with("images")->find($id);
            if(!$course){
              return response()->json(["status"=>"error","massage"=>"course not found"],404);
            }
            $review=Review::where("course_id","=",$id)->get()->toArray();
            if(count($review)>0){
                $userIds=array_column($review,'user_id');
                $user=getUserByIds($userIds);
                // echo "<pre>".print_r($user)."</pre>";
                if($user["status"]==="error"){
                    $review=[];
                }else{
                    foreach($review as$key=>$reviews){
                        $userIndex=array_search($reviews["user_id"],array_column($user["data"],'id'));
                        $review[$key]['user'] = $user['data'][$userIndex];
                    }
                }
            }
            $totalStudent=Mycourse::where("course_id","=",$id)->count();
            $course["reviews"]=$review;
            $totalVideos=Chapter::where("course_id","=",$id)->withCount("lesson")->get()->toArray();
            $finalTotalVideos=array_sum(array_column($totalVideos,'lesson_count'));
             $course["total_student"]=$totalStudent;
             $course["total_videos"]=$finalTotalVideos;
                //  echo "<pre>".print_r($finalTotalVideos)."</pre>";
            return response()->json(["status"=>"succes","data"=>$course]);
        }

    public function create(Request $request){
           $rules=[
            "name"=>"required|string",
            "certificate"=>"required|boolean",
            "thumbnail"=>"string|url",
            "type"=>"required|in:free,preemium",
            "status"=>"required|in:draft,published",
            "price"=>"integer",
            "level"=>"required|in:all-level,beginner,intermediate,advanced",
            "mentor_id"=>"required|integer",
            "description"=>"string",
        ];
        $data=$request->all();
        $validator =Validator::make($data,$rules);
        if($validator->fails()){
            return response()->json(["status"=>"error","massage"=>$validator->errors()],400);
        }
        $mentorId=$request->input("mentor_id");
        $mentor=Mentor::find($mentorId);
        if(!$mentor){
            return response()->json(["status"=>"error","massage"=>"mentor not found"],404);

        }
        $course =Course::create($data);
        return response()->json(["status"=>"succes","data"=>$course]);
    }
    public function update(Request $request,$id){
            $rules=[
                        "name"=>"string",
                        "certificate"=>"boolean",
                        "thumbnail"=>"string|url",
                        "type"=>"in:free,preemium",
                        "status"=>"in:draft,published",
                        "price"=>"integer",
                        "level"=>"in:all-level,beginner,intermediate,advanced",
                        "mentor_id"=>"integer",
                        "description"=>"string",
            ];

            $course =Course::find($id);
            if(!$course){
                  return response()->json(["status"=>"error","massage"=>"course not found"],404);

            }
            $mentorId=$request->input("mentor_id");
            if($mentorId){
                $mentor =Mentor::find($mentorId);
                if(!$mentor){
                return response()->json(["status"=>"error","massage"=>"mentor not found"],404);
                }
            }
            $data= $request->all();
            $course->fill($data);
            $course->save();
            return response()->json(["status"=>"succes","data"=>$course]);
    }
    public function destroy($id){
        $course=Course::find($id);
        if(!$course){
            return response()->json(["status"=>"error","massage"=>"course not found"]);
        }
        $course->delete();
        return response()->json(["status"=>"succes","massage"=>"course deleted"]);

    }
}
