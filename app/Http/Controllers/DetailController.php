<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\School_grade;
use App\Http\Requests\SchoolRequest;
use App\Http\Requests\ResultRequest;
use Illuminate\Support\Facades\Log;
use Exception;


class DetailController extends Controller

{

    public function EntryView(){//参考create
       
        return view('seito.entry');
    }
    
    public function store(SchoolRequest $request )
    {
        $validatedData = $request->validated(); // リクエストのバリデーション
        $path = null; // デフォルトのパスを設定

        if ($request->hasFile('img_path')) {
            $image = $request->file('img_path');
            $path = $image->store('items', 'public'); // 画像のアップロード
        }

        try {
            $data =[
                'name' => $validatedData['name'],
                'address' =>$validatedData['address'],
                'img_path' =>$path,
                'comment' =>$validatedData['comment'] ?? null,

            ];
            Student::storeStudent($data);
            
            return redirect()->route('seito.gakuseientry')->with('success', '登録が完了しました。');
        } catch (Exception $e) {
            Log::error('学生登録のエラー(store): ' . $e->getMessage());
            return redirect()->back()->withErrors('登録に失敗しました。');
        }
    }
    
    
    
  
  

    public function StudentEdit(Student $student){ //参考edit gakuseihenshu
        return view('seito.studentedit',compact('student'));//compactでコントローラーからviewへデータを受け渡しする
    }
    
    public function update(SchoolRequest $request ,$id){//学生編集フォーム・更新
        $validatedData = $request->validated();
         try{
            $data =[
                'name' =>$validatedData['name'],
                'address' =>$validatedData['address'],
                'grade' => $validatedData['grade'], 
                'comment' =>$validatedData['comment'],
            ];
            if($request->hasFile('img_path')){
                $data['img_path'] =$request->file('img_path')->store('items','public');
            }
            Student::updateStudent($id, $data);
            $student = Student::findOrFail($id);

             return redirect()->route('seito.studentedit', ['student' => $student->id])->with('success', '学生情報が更新されました');
            }catch(Exception $e){
                Log::error('学生情報更新エラー(update):' . $e->getMessage());
                return redirect()->back()->withErrors('更新に失敗しました');
            }
         }
    
    // destroy　削除
    public function delete($id)
{
    try {
        // 学生を削除
        Student::destroyStudent($id);
        Log::info('Student deleted successfully with ID: ' . $id); // 削除が成功したかを確認
        return redirect()->route('seito.gakuseihyouji')->with('success', '学生が削除されました');
    } catch (Exception $e) {
        Log::error('学生削除エラー: ' . $e->getMessage());
        return redirect()->back()->withErrors('削除に失敗しました。');
    }
}

    public function GradeRegister($id){//成績登録フォームへのルート・画面遷移
        try{
            $student =Student::findOrFail($id);
           
            return view('seito.graderegister',compact('student'));
        } catch(Exception $e){
            Log::error('成績登録時エラー(graderegister):' . $e->getMessage());
            return redirect()->back()->withErrors('学生情報の取得に失敗しました');
        }
    }
  
    
    
    public function storeGrade(ResultRequest $request, $id) {
        try {
            $validatedData =$request->validated();
            School_grade::storeGrade($validatedData, $id);
            return redirect()->route('seito.graderegister', ['student' => $id])
                ->with('success', '成績情報が登録されました。');
        } catch (Exception $e) {
            Log::error('成績登録エラー(storeGrade): ' . $e->getMessage());
            return redirect()->back()->withErrors('成績登録ができませんでした。');
        }
    }


    public function GradeEdit($id){//変更・修正をここでする
        try{

            $subject = School_grade::findOrFail($id);
            $student = $subject->student; 
            return view('seito.gradeedit', compact('subject','student'));
        } catch(Exception $e){
            Log::error('学生情報編集エラー(GradeEdit):' . $e->getMessage());
            return redirect()->back()->withErrors('学生情報の取得ができませんでした。');
        }

    }
    
    
    public function UpdateGradeEdit(ResultRequest $request, $id){//更新処理
        $validatedData = $request->validated();
        // dd($validatedData);

        try{

            $subject = School_grade::findOrFail($id); // IDで対象の成績データを取得
            School_grade::updateGrade($validatedData, $subject->id); 
            return redirect()->route('seito.gradeedit', ['subject' => $subject->id])->with('success', '成績情報が更新されました');
            
        } catch(Exception $e){
            Log::error('成績情報更新エラー(UpdateGrade):' . $e->getMessage());
            return redirect()->back()->withErrors('成績情報の更新に失敗しました。');
        }
    }
    
 
}


