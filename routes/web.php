<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {  return view('login');});
Route::get('/login', function () {  return view('login');});
Route::get('/listuser', function () {  return view('ListUser');});
Route::get('/report', function () {  return view('Report');});
Route::get('/userpost', function () {  return view('UserPost');});
Route::get('/profile', function () {  return view('profile');});
  

