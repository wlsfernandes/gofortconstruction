<?php

use App\Http\Controllers\Site\BlogPostController;
use App\Http\Controllers\Site\ContactSubmissionController;
use App\Http\Controllers\Site\FaqController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\ProjectController;
use App\Http\Controllers\Site\ReviewController;
use App\Http\Controllers\Site\ServiceAreaController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::view('/about', 'site.about')->name('about');
Route::view('/services', 'site.services')->name('services');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::view('/process', 'site.process')->name('process');
Route::get('/reviews', ReviewController::class)->name('reviews');
Route::get('/faq', FaqController::class)->name('faq');
Route::get('/service-areas', ServiceAreaController::class)->name('service-areas');
Route::get('/blog', [BlogPostController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogPostController::class, 'show'])->name('blog.show');
Route::view('/contact', 'site.contact')->name('contact');
Route::post('/contact', [ContactSubmissionController::class, 'store'])
    ->middleware('throttle:contact-form')
    ->name('contact.store');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function (): void {
    Route::view('/', 'admin.dashboard')->name('dashboard');
});
