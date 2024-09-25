<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainmenuController;
use App\Http\Controllers\DetailController;

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
Route::get('seito/entry', [DetailController::class, 'EntryView'])->name('seito.entry');
Route::post('seito/store', [DetailController::class, 'store'])->name('seito.store');

Route::get('seito/studentedit/{student}', [DetailController::class, 'StudentEdit'])->name('seito.studentedit');
Route::post('seito/studentedit/{student}', [DetailController::class, 'update'])->name('update');
Route::post('/students/{id}', [DetailController::class, 'delete'])->name('students.delete');

Route::get('seito/graderegister/{student}', [DetailController::class, 'GradeRegister'])->name('seito.graderegister');
Route::post('seito/graderegister/{student}', [DetailController::class, 'storeGrade'])->name('seito.graderegister_post');

Route::get('seito/gradeedit/{subject}', [DetailController::class, 'GradeEdit'])->name('seito.gradeedit');
Route::post('seito/gradeedit/{subject}', [DetailController::class, 'UpdateGradeEdit'])->name('seito.updategrade');
//学年更新
Route::post('seito/update-grades',[MainmenuController::class,'UpdateGrades'])->name('seito.update-grades');
