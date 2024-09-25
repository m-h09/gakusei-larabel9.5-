<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainmenuController;
use App\Http\Controllers\ShosaiController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// mainmenu
Route::get('seito/mainmenu', [MainmenuController::class, 'MenuView'])->name('seito.mainmenu');
Route::post('seito/mainmenu', [MainmenuController::class, 'MenuView'])->name('seito/mainmenu');

Route::get('seito/studentdisplay', [MainmenuController::class, 'StudentAll'])->name('seito.studentdisplay');
Route::post('seito/studentdisplay', [MainmenuController::class, 'StudentAll'])->name('seito.studentdisplay_post');
Route::get('seito/studentdetail/{student}', [MainmenuController::class, 'DetailIndividual'])->name('seito.studentdetail');

// shosai
Route::get('seito/entry', [ShosaiController::class, 'EntryView'])->name('seito.entry');
Route::post('seito/store', [ShosaiController::class, 'store'])->name('seito.store');

Route::get('seito/studentedit/{student}', [ShosaiController::class, 'StudentEdit'])->name('seito.studentedit');
Route::post('seito/studentedit/{student}', [ShosaiController::class, 'update'])->name('update');
Route::post('/students/{id}', [ShosaiController::class, 'delete'])->name('students.delete');

Route::get('seito/graderegister/{student}', [ShosaiController::class, 'GradeRegister'])->name('seito.graderegister');
Route::post('seito/graderegister/{student}', [ShosaiController::class, 'storeGrade'])->name('seito.graderegister_post');

Route::get('seito/gradeedit/{subject}', [ShosaiController::class, 'GradeEdit'])->name('seito.gradeedit');
Route::post('seito/gradeedit/{subject}', [ShosaiController::class, 'UpdateGradeEdit'])->name('seito.updategrade');
//学年更新
Route::post('seito/update-grades',[MainmenuController::class,'UpdateGrades'])->name('seito.update-grades');
