<?php

use App\Http\Controllers\DemoController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{package}', [PackageController::class, 'show'])->name('packages.show');

Route::get('/how-it-works', [PageController::class, 'howItWorks'])->name('how-it-works');
Route::get('/custom-and-it', [PageController::class, 'itServices'])->name('it-services');
Route::get('/demos', [PageController::class, 'demos'])->name('demos');
Route::get('/demos/{slug}', [DemoController::class, 'show'])->name('demos.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

Route::get('/contact', [EnquiryController::class, 'create'])->name('contact');
Route::post('/contact', [EnquiryController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');
Route::get('/contact/thank-you', [EnquiryController::class, 'thanks'])->name('contact.thanks');
