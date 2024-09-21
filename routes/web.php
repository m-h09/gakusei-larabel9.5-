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

Route::get('seito/gakuseihyouji', [MainmenuController::class, 'StudentAll'])->name('seito.gakuseihyouji');
Route::post('seito/gakuseihyouji', [MainmenuController::class, 'StudentAll'])->name('seito.gakuseihyouji_post');
Route::get('seito/gakuseishosai/{student}', [MainmenuController::class, 'Shosaikojin'])->name('seito.Shosaikojin');

// shosai
Route::get('seito/gakuseientry', [ShosaiController::class, 'EntryView'])->name('seito.gakuseientry');
Route::post('seito/store', [ShosaiController::class, 'store'])->name('seito.store');

Route::get('seito/gakuseihenshu/{student}', [ShosaiController::class, 'gakuseiHenshu'])->name('seito.gakuseihenshu');
Route::post('seito/gakuseihenshu/{student}', [ShosaiController::class, 'update'])->name('update');
Route::post('/students/{id}', [ShosaiController::class, 'delete'])->name('students.delete');

Route::get('seito/gakuseiseiseki/{student}', [ShosaiController::class, 'gakuseiSeiseki'])->name('seito.gakuseiseiseki');
Route::post('seito/gakuseiseiseki/{student}', [ShosaiController::class, 'storeSeiseki'])->name('seito.gakuseiseiseki_post');

Route::get('seito/seisekihenshu/{subject}', [ShosaiController::class, 'seisekiHenshu'])->name('seito.seisekihenshu');
Route::post('seito/seisekihenshu/{id}', [ShosaiController::class, 'updateSeiseki'])->name('seito.updateseiseki');
//学年更新
Route::post('seito/update-grades',[MainmenuController::class,'updateGrades'])->name('seito.update-grades');
