<?php

use Contensio\Testimonials\Http\Controllers\Admin\TestimonialController;
use Contensio\Testimonials\Http\Controllers\Frontend\SubmitController;
use Illuminate\Support\Facades\Route;

// ── Admin routes ─────────────────────────────────────────────────────────────

Route::prefix(config('contensio.route_prefix', 'account'))
    ->middleware(['web', 'contensio.auth', 'contensio.admin'])
    ->group(function () {
        Route::get('/testimonials',             [TestimonialController::class, 'index'])  ->name('testimonials.index');
        Route::get('/testimonials/create',      [TestimonialController::class, 'create']) ->name('testimonials.create');
        Route::post('/testimonials',            [TestimonialController::class, 'store'])  ->name('testimonials.store');
        Route::get('/testimonials/{id}/edit',   [TestimonialController::class, 'edit'])   ->name('testimonials.edit');
        Route::put('/testimonials/{id}',        [TestimonialController::class, 'update']) ->name('testimonials.update');
        Route::post('/testimonials/{id}/approve',[TestimonialController::class, 'approve'])->name('testimonials.approve');
        Route::post('/testimonials/{id}/reject', [TestimonialController::class, 'reject']) ->name('testimonials.reject');
        Route::delete('/testimonials/{id}',     [TestimonialController::class, 'destroy'])->name('testimonials.destroy');
    });

// ── Public form submission ────────────────────────────────────────────────────

Route::middleware('web')->group(function () {
    Route::post('/testimonials/submit', [SubmitController::class, 'submit'])->name('testimonials.submit');
});
