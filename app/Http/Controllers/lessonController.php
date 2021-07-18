<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chapter;
use App\Lessons;
use Illuminate\Support\Facades\Validator;
class lessonController extends Controller
{   
    public function index(Request $request){
        $lessons =Lessons::query();
        $chapterId=$request->query("chapter_id");
        $lessons->when($chapterId,function($query)use($chapterId){
            return $query->where("chapter_id","=",$chapterId);
        });
        return response()->json(["status"=>"succes","data"=>$lessons->get()]);
    }
    public function show($id){
        $lessons =Lessons::find($id);
        if(!$lessons){
            return response()->json(["status"=>"error","massage"=>"lessons not found"],404);
        }    
        return response()->json(["status"=>"succes","data"=>$lessons],404);
    }
    public function create(Request $request){
        $rules=["name"=>"required|string","video"=>"required|string","chapter_id"=>"required|integer"];
        $data=$request->all();
        $validator=Validator::make($data,$rules);
        if($validator->fails()){
            return response()->json(["status"=>"error","massage"=>$validator->errors()],400);
        }

        $chapterId=$request->input("chapter_id");
        $chapter=Chapter::find($chapterId);
        if(!$chapter){
          return response()->json(["status"=>"error","massage"=>"chapter not found"],404);
        }
        $lessons =Lessons::create($data);
        return response()->json(["status"=>"success","data"=>$lessons]); 
    }
    public function update(Request $request ,$id){
        $rules=["name"=>"string","video"=>"string","chapter_id"=>"integer"];
        $data=$request->all();
        $lessons=Lessons::find($id);
        if(!$lessons){
            return response()->json(["status"=>"error","massage"=>"lessons not found"],404);
        }
        $validator=Validator::make($data,$rules);
        if($validator->fails()){
            return response()->json(["status"=>"error","massage"=>$validator->errors()],400);
        }
        $chapterId=$request->input("chapter_id");
        if($chapterId){
            $chapter=Chapter::find($chapterId);
            if(!$chapter){
                return response()->json(["status"=>"error","massage"=>"chapter not found"],404);
            }
        }
        $lessons->fill($data);
        $lessons->save();
        return response()->json(["status"=>"succes","data"=>$lessons]);
    }

    public function destroy($id){
        $lessons=Lessons::find($id);
        if(!$lessons){
            return response()->json(["status"=>"error","massage"=>"lessons not found"],404);
        }
        $lessons->delete();
        return response()->json(["status"=>"succes","masage"=>"lessons deleted"]);
    }
}
