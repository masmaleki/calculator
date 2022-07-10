<?php

/*
|--------------------------------------------------------------------------
| Mautic Application Register
|--------------------------------------------------------------------------
|
*/


Route::view('/', 'calculator::calc')->name('calc.index');
Route::post('/', 'calcController@calc')->name('calc');