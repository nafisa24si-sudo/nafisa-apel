<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if table exists, if not create it
        if (!Schema::hasTable('pelanggan_attachments')) {
            Schema::create('pelanggan_attachments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pelanggan_id');
                $table->string('file_name');
                $table->string('file_path');
                $table->string('file_type');
                $table->unsignedBigInteger('file_size');
                $table->timestamps();

                $table->foreign('pelanggan_id')->references('pelanggan_id')->on('pelanggan')->onDelete('cascade');
            });
        }

        // Check if profile_photo column exists in users, if not add it
        if (!Schema::hasColumn('users', 'profile_photo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('profile_photo')->nullable()->after('password');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop table if exists
        if (Schema::hasTable('pelanggan_attachments')) {
            Schema::dropIfExists('pelanggan_attachments');
        }

        // Drop column if exists
        if (Schema::hasColumn('users', 'profile_photo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('profile_photo');
            });
        }
    }
};
