<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL does not support Postgres-style JSON expression indexes.
        // Untuk kompatibilitas MySQL, migration ini tidak membuat index expression.
        // Filter yang dipakai ada di PaymentController via:
        //   JSON_UNQUOTE(JSON_EXTRACT(payload, '$.confirmed_as'))
        // Optimasi untuk MySQL biasanya memakai generated column + index (di luar scope migration ini).

        // Intentionally no-op for MySQL.
        // no-op

        // If you later switch to Postgres, replace this migration with the expression index.
        return;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS payments_payload_confirmed_as_idx');
    }
};
