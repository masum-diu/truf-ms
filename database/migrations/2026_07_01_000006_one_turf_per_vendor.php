<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicateOwners = DB::table('turfs')
            ->select('owner_id')
            ->groupBy('owner_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('owner_id');

        foreach ($duplicateOwners as $ownerId) {
            $keepId = DB::table('turfs')->where('owner_id', $ownerId)->orderBy('id')->value('id');
            DB::table('turfs')->where('owner_id', $ownerId)->where('id', '!=', $keepId)->delete();
        }

        Schema::table('turfs', function (Blueprint $table) {
            $table->unique('owner_id');
        });
    }

    public function down(): void
    {
        Schema::table('turfs', function (Blueprint $table) {
            $table->dropUnique(['owner_id']);
        });
    }
};
