<?php

use App\Http\Controllers\DemoController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SolutionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{package}', [PackageController::class, 'show'])->name('packages.show');

// Revamp: the services hub, line pages and service pages (staging only until launch).
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->where('slug', '[a-z0-9-]+')->name('services.show');

// Revamp phase 3: problems, industries and the work we can show.
Route::get('/solutions', [SolutionController::class, 'index'])->name('solutions.index');
Route::get('/solutions/{slug}', [SolutionController::class, 'show'])->where('slug', '[a-z0-9-]+')->name('solutions.show');
Route::get('/industries', [IndustryController::class, 'index'])->name('industries.index');
Route::get('/industries/{slug}', [IndustryController::class, 'show'])->where('slug', '[a-z0-9-]+')->name('industries.show');
Route::get('/work', [PageController::class, 'work'])->name('work');

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

// Project enquiries. 404 unless REVAMP_LEADS is on (needs the D7 migration).
Route::get('/quote', [QuoteController::class, 'create'])->name('quote');
Route::post('/quote', [QuoteController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('quote.store');
Route::get('/quote/thank-you', [QuoteController::class, 'thanks'])->name('quote.thanks');
