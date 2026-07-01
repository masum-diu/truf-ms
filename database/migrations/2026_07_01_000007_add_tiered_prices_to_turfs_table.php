<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('turfs', function (Blueprint $table) {
            $table->unsignedInteger('day_price')->default(0)->after('price_per_hour');
            $table->unsignedInteger('night_price')->default(0)->after('day_price');
            $table->unsignedInteger('offday_price')->default(0)->after('night_price');
        });

        DB::table('turfs')->update([
            'day_price' => DB::raw('price_per_hour'),
            'night_price' => DB::raw('price_per_hour'),
            'offday_price' => DB::raw('price_per_hour'),
        ]);
    }

    public function down(): void
    {
        Schema::table('turfs', function (Blueprint $table) {
            $table->dropColumn(['day_price', 'night_price', 'offday_price']);
        });
    }
};
