<?php

use Illuminate\Support\Facades\Route;

Route::post('completeInfo','completeInfo');
//->middleware('verified')

Route::get('specialties/show','updateSpecialty');

Route::post('specialties/edit','editSpecialty');

Route::get('user/show','showAnotherProfile');

Route::get('profile/show','showMyProfile');
