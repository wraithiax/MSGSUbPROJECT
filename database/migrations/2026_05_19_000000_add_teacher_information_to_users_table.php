<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('fname')->nullable()->after('role');
            $table->string('mname')->nullable()->after('fname');
            $table->string('lname')->nullable()->after('mname');
            $table->string('contact')->nullable()->after('lname');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['fname', 'mname', 'lname', 'contact']);
        });
    }
};
