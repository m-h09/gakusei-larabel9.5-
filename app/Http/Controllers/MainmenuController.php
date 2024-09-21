<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\School_grade;
use Illuminate\Support\Facades\Log;
use Exception;

class MainmenuController extends Controller
{
    public function MenuView(Request $request){
        // dd($request);
        return view('seito.mainmenu');
    }

    public function StudentAll(Request $request)
{
    try {
        $search = $request->input('search');
        $grade = $request->input('grade');

        // 学生を取得
        $students = Student::StudentAll($search, $grade);

        return view('seito.gakuseihyouji', compact('students'));
    } catch (\Exception $e) {
        Log::error('学生表示エラー: ' . $e->getMessage());
        return redirect()->back()->withErrors('学生情報の取得中にエラーが発生しました。');
    }
}


    //学年更新ボタン
    public function updateGrades(){
        Student::updateGrades();
        return redirect()->route('seito.mainmenu')->with('success', '学年が更新されました');
    }

    //選択した学生の学生詳細画面をみる
    public function Shosaikojin($id, Request $request)
    {
          $student = Student::findOrfail($id);
          $gradeAll = School_grade::filterGrades($id,$request->input('grade'),$request->input('term'));

            //setRelationでフィルタリング後のデータをリレーションとして置き換える
            $student->setRelation('subjects',$gradeAll);
    
        return view('seito.gakuseishosai', compact('student'));
    
       
    }
    
    

}

