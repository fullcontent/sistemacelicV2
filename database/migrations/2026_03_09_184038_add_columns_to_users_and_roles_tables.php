<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('group_type', 50)->default('CLIENTE')->after('email_verified_at');
            $table->unsignedBigInteger('client_id')->nullable()->after('group_type');
            $table->boolean('status')->default(true)->after('client_id');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->integer('hierarchy_level')->default(5)->after('guard_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['group_type', 'client_id', 'status']);
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['hierarchy_level']);
        });
    }
};
