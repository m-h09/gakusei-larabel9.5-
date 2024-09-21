<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\School_grade;
use App\Http\Requests\SchoolRequest;
use App\Http\Requests\ResultRequest;
use Illuminate\Support\Facades\Log;
use Exception;


class ShosaiController extends Controller

{

    public function EntryView(){//参考create
       
        return view('seito.gakuseientry');
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
    
    
    
  
  

    public function gakuseiHenshu(Student $student){ //参考edit
        return view('seito.gakuseihenshu',compact('student'));//compactでコントローラーからviewへデータを受け渡しする
    }
    
    public function update(SchoolRequest $request ,$id){//学生編集フォーム・更新
        $validatedData = $request->validated();
         try{
            $data =[
                'name' =>$validatedData['name'],
                'address' =>$validatedData['address'],
                'comment' =>$validatedData['comment'],
            ];
            if($request->hasFile('img_path')){
                $data['img_path'] =$request->file('img_path')->store('items','public');
            }
            Student::updateStudent($id, $data);
            $student = Student::findOrFail($id);

             return redirect()->route('seito.gakuseihenshu', ['student' => $student->id])->with('success', '学生情報が更新されました');
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

    public function gakuseiSeiseki($id){//成績登録フォームへのルート・画面遷移
        try{
            $student =Student::findOrFail($id);
            return view('seito.gakuseiseiseki',compact('student'));
        } catch(Exception $e){
            Log::error('成績登録時エラー(gakuseiSeiseki):' . $e->getMessage());
            return redirect()->back()->withErrors('学生情報の取得に失敗しました');
        }
    }
  
    
    
    public function storeSeiseki(ResultRequest $request, $id) {
        try {
            $validatedData = $request->validated();
            $subject = new School_grade(); // 新しい成績オブジェクトを作成
            
            $subject->student_id = $id; // student_id に値を設定
            $subject->grade = $validatedData['grade'];
            $subject->term = $validatedData['term'];
            $subject->japanese = $validatedData['japanese'];
            $subject->math = $validatedData['math'];
            $subject->science = $validatedData['science'];
            $subject->social_studies = $validatedData['social_studies'];
            $subject->music = $validatedData['music'];
            $subject->home_economics = $validatedData['home_economics'];
            $subject->english = $validatedData['english'];
            $subject->art = $validatedData['art'];
            $subject->health_and_physical_education = $validatedData['health_and_physical_education'];
            
            $subject->save(); // データベースに保存
            
            return redirect()->route('seito.gakuseiseiseki', ['student' => $id])
                ->with('success', '成績情報が登録されました。');
        } catch (Exception $e) {
            Log::error('成績登録エラー(storeSeiseki): ' . $e->getMessage());
            return redirect()->back()->withErrors('成績登録ができませんでした。');
        }
    }


    public function seisekiHenshu($id){//変更・修正をここでする
        try{

            $subject = School_grade::findOrFail($id);
            $student = $subject->student; 
            return view('seito.seisekihenshu', compact('subject','student'));
        } catch(Exception $e){
            Log::error('学生情報編集エラー(gakuseiHenshu):' . $e->getMessage());
            return redirect()->back()->withErrors('学生情報の取得ができませんでした。');
        }

    }
    
    
    public function updateSeiseki(ResultRequest $request, $id){//更新処理
        $validatedData = $request->validate([
            'grade' => 'required|integer',
            'term' => 'required|integer',
            'japanese' => 'required|integer',
            'math' => 'required|integer',
            'science' => 'required|integer',
            'social_studies' => 'required|integer',
            'music' => 'required|integer',
            'home_economics' => 'required|integer',
            'english' => 'required|integer',
            'art' => 'required|integer',
            'health_and_physical_education' => 'required|integer',
        ]);

        try{

            $validatedData =$request->validated();
            $subject = School_grade::findOrFail($id);
            //モデルのメソッド使用
            $subject->updateGrade($validatedData);
            $subject->save();
            return redirect()->route('seito.seisekihenshu', ['subject' => $subject->id])->with('success', '成績情報が更新されました');
            
        } catch(Exception $e){
            Log::error('成績情報更新エラー(updateSeiseki):' . $e->getMessage());
            return redirect()->back()->withErrors('成績情報の更新に失敗しました。');
        }
    }
    
 
}


