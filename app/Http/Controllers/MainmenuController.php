<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\School_grade;

class MainmenuController extends Controller
{
    public function MenuView(Request $request){
        // dd($request);
        return view('seito.mainmenu');
    }

    public function StudentAll(Request $request){
        //学年・名前検索
        $keyword = Student::query();
        // 名前で検索
        if($request->filled('search')){
            $search = $request->input('search');
            $keyword->where('name', 'like', '%'.$search.'%'); // 変数$searchを使用して部分一致検索
        }
    
        // 学年で検索
        //!==の意味は〜でない場合
        if($request->filled('grade') && $request->input('grade') !== '' && $request->input('grade') !== '学生を選択してください'){
            $grade = $request->input('grade');
            $keyword->where('grade', 'like', '%'.$grade.'年%'); // 変数$gradeを使用して部分一致検索
        }
        

        //学年、非表示の分
        else {
            // 学年が指定されていない場合は全ての学生を表示
            $keyword->where(function($query) {
                $query->where('grade', '<', 7)//7以上のものをwhereでフィルタリング
                      ->orWhereNull('grade'); // 学年が空の場合も表示、orWhereNullでgradeがnullのものも表示
            });
        }
        
        //一度の表示件数
        $students = $keyword->paginate(10);
        return view('seito.gakuseihyouji',['students' => $students]);
    }
    //学年更新ボタン
    public function updateGrades(){
        $students = Student::all();
        foreach ($students as $student) {
            if (is_numeric($student->grade) && $student->grade < 6) {
                $student->grade += 1;
                $student->save();
            }
        }
        return redirect()->route('seito.mainmenu')->with('success', '学年が更新されました');
    }

    //選択した学生の学生詳細画面をみる
    public function Shosaikojin($id, Request $request)
    {
          $student =Student::findOrFail($id);
          
          $gradeAll= School_grade::where('student_id',$id);

            //学年フィルタリング
            if ($request->filled('grade')) {
                $gradeAll->where('grade', $request->input('grade'));
            }
            //学期フィルタリング
            if ($request->filled('term')) {
                $gradeAll->where('term', $request->input('term'));
            }
            //学年、学期をまとめる
            $filtersubjects =$gradeAll->get();

            //setRelationでフィルタリング後のデータをリレーションとして置き換える
            $student->setRelation('subjects',$filtersubjects);
    
        return view('seito.gakuseishosai', compact('student'));
    
       
    }
    
    

}

