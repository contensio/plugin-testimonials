<?php

use Contensio\Testimonials\Http\Controllers\Admin\TestimonialController;
use Contensio\Testimonials\Http\Controllers\Frontend\SubmitController;
use Illuminate\Support\Facades\Route;

// ── Admin routes ─────────────────────────────────────────────────────────────

Route::prefix(config('contensio.route_prefix', 'account'))
    ->middleware(['web', 'contensio.auth', 'contensio.admin'])
    ->group(function () {
        Route::get('/testimonials',             [TestimonialController::class, 'index'])  ->name('contensio-testimonials.index');
        Route::get('/testimonials/create',      [TestimonialController::class, 'create']) ->name('contensio-testimonials.create');
        Route::post('/testimonials',            [TestimonialController::class, 'store'])  ->name('contensio-testimonials.store');
        Route::get('/testimonials/{id}/edit',   [TestimonialController::class, 'edit'])   ->name('contensio-testimonials.edit');
        Route::put('/testimonials/{id}',        [TestimonialController::class, 'update']) ->name('contensio-testimonials.update');
        Route::post('/testimonials/{id}/approve',[TestimonialController::class, 'approve'])->name('contensio-testimonials.approve');
        Route::post('/testimonials/{id}/reject', [TestimonialController::class, 'reject']) ->name('contensio-testimonials.reject');
        Route::delete('/testimonials/{id}',     [TestimonialController::class, 'destroy'])->name('contensio-testimonials.destroy');
    });

// ── Public form submission ────────────────────────────────────────────────────

Route::middleware('web')->group(function () {
    Route::post('/testimonials/submit', [SubmitController::class, 'submit'])->name('contensio-testimonials.submit');
});
