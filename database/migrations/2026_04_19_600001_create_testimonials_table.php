<?php

/**
 * Testimonials — Contensio plugin.
 * https://contensio.com
 *
 * @copyright   Copyright (c) 2026 Iosif Gabriel Chimilevschi
 * @license     https://www.gnu.org/licenses/agpl-3.0.txt  AGPL-3.0-or-later
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('role', 150)->nullable();     // job title or role
            $table->string('company', 150)->nullable();  // company or organisation
            $table->string('avatar_url', 500)->nullable();
            $table->text('content');                     // the testimonial body
            $table->unsignedTinyInteger('rating')->nullable(); // 1–5, optional
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->string('source', 50)->nullable();    // e.g. 'form', 'manual'
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
