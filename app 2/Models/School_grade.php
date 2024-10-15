<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Facades\DB;
use Illuminate\Database\Facades\Log;


class School_grade extends Model
{
    use HasFactory;
    protected $table = 'school_grades';
    protected $primaryKey ='id';
    public $incrementing = true;
    protected $fillable = [
      // 'student_id',
      'grade', 
      'term', 
      'japanese', 
      'math', 
      'science', 
      'social_studies', 
      'music', 
      'home_economics', 
      'english', 
      'art', 
      'health_and_physical_education'
  ];

    
    public static function filterGrades($studentId, $grade = null, $term = null){
      //成績登録を新規で行うためtry catch文を使う
          try {
              $query = self::where('student_id', $studentId);
  
              if ($grade) {
                  $query->where('grade', $grade);
              }
  
              if ($term) {
                  $query->where('term', $term);
              }
  
              return $query->get();
          } catch (Exception $e) {
              Log::error('学生成績エラー: ' . $e->getMessage());
              return [];
          }
      }



    public static function storeGrade($validated,$studentId){
      
     

        $subject = new self();
        $subject ->student_id=$studentId;

        $subject ->fill($validated);
        $subject ->save();
       
     }
     public static function updateGrade($data, $id)
     {
         try {
             $grade = self::findOrFail($id); // 静的メソッドなので、self::を使用
             $grade->fill($data); // データを更新
             $grade->save();
         } catch (Exception $e) {
             Log::error('成績情報更新エラー(UpdateGrade):' . $e->getMessage());
             throw $e; // 再度例外を投げてコントローラー側で処理
         }
     }
    public function student()
        {
            return $this->belongsTo(Student::class ,'student_id');
        }

}
