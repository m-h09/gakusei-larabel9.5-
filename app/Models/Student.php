<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;


class Student extends Model
{

    use HasFactory;
    protected $table ='students';
    protected $primaryKey ='id';
    public $incrementing = true;
    protected $fillable = ['name', 'address','grade', 'img_path','comment'];
    
    

  
  //入力されたものからデータベースへ情報を取得する
  public static function StudentAll($search = null, $grade = null) {
    try {
        $query = self::query();

        // 名前検索
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // 学年検索
        if (!empty($grade) && $grade !== '学年を選択してください') {
            $query->where('grade', $grade);
        }

        return $query->paginate(10);
    } catch (Exception $e) {
        Log::error('学生情報取得エラー(StudentAll):' . $e->getMessage());
        return [];
    }
}

   
    //学生データの登録
    public static function storeStudent($data){
        try{
          return self::create($data);
        }catch(Exception $e){
        Log::error('学生登録エラー(store):' . $e->getMessage());
        }
      
      
    }
    //学生更新
    public static function updateStudent($id, array $data){//学生編集フォーム・更新
      
       try{
          $student = self::findOrFail($id);
          $student->fill($data);
          $student->save();
          return $student;
          }catch(Exception $e){
              Log::error('学生情報更新エラー(update):' . $e->getMessage());
              
          }
       }
    
    //学生削除
    public static function destroyStudent($id){
      try {
          $student = self::findOrFail($id);//静的メソッド、Controllerで$thisは使えない
          //動的メソッドの時のみ$thisが使える
          $student->delete();
      } catch (Exception $e){
          Log::error('学生削除時エラー（destroy):' . $e->getMessage());
      }
  }
  //学年の一括更新
  public static function updateGrades(){
    try{
      $students= self::all();
      foreach($students as $student){

        if(is_numeric($student->grade) && $student->grade < 6){
          $student ->grade += 1;
          $student -> save();
        }
      }
      
    } catch (Exception $e){
      Log::error('学年更新エラー(updateGrades):' . $e->getMessage());
    }
  }
  
    public function subjects(){
      return $this->hasMany(School_grade::class,'student_id');
    }
  

  }