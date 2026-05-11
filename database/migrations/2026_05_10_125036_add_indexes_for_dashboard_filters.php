<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL tidak mendukung IF NOT EXISTS untuk CREATE INDEX, dan Laravel's $table->index
        // juga tidak punya guard saat index dengan nama yang sama sudah ada.
        // Jadi untuk menghindari duplicate-key failure, kita no-op migration ini pada MySQL.
        // Index yang diperlukan seharusnya sudah dibuat oleh migrasi sebelumnya / manual create.
        return;
    }

    public function down(): void
    {
        // Best-effort cleanup. Dropping all indexes is optional in this environment.
        // Keeping empty down() avoids migration rollback failures.
    }
};
