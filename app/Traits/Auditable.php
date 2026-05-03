<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

/**
 * @method static void created(\Closure $callback)
 * @method static void updated(\Closure $callback)
 * @method static void deleted(\Closure $callback)
 */

trait Auditable
{
    protected static function bootAuditable()
    {
        // CREATE
        static::created(function ($model) {
            $attributes = collect($model->getAttributes())
                ->except(['created_at', 'updated_at'])
                ->toArray();

            AuditLog::create([
                'table_name' => $model->getTable(),
                'record_id' => $model->getKey(),
                'action' => 'insert',
                'old_values' => null,
                'new_values' => $model->getAttributes(),
                'changed_by' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'changed_at' => now(),
            ]);
        });

        // UPDATE
        static::updated(function ($model) {
            $changes = $model->getChanges();

            // Hindari log kosong
            if (empty($changes)) {
                return;
            }

            AuditLog::create([
                'table_name' => $model->getTable(),
                'record_id' => $model->getKey(),
                'action' => 'update',
                'old_values' => array_intersect_key($model->getOriginal(), $changes),
                'new_values' => $changes,
                'changed_by' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'changed_at' => now(),
            ]);
        });

        // DELETE
        static::deleted(function ($model) {
            AuditLog::create([
                'table_name' => $model->getTable(),
                'record_id' => $model->getKey(),
                'action' => 'delete',
                'old_values' => $model->getAttributes(),
                'new_values' => null,
                'changed_by' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'changed_at' => now(),
            ]);
        });
    }
}
