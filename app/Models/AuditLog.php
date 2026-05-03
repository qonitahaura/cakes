<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    public $timestamps = false;

    protected $fillable = [
        'table_name',
        'record_id',
        'action',
        'old_values',
        'new_values',
        'changed_by',
        'ip_address',
        'user_agent',
        'changed_at'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public static function log($model, string $action, $old = null, $new = null)
    {
        return self::create([
            'table_name' => $model->getTable(),
            'record_id' => $model->getKey(),
            'action' => $action,
            'old_values' => $old,
            'new_values' => $new,
            'changed_by' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'changed_at' => now(),
        ]);
    }
}
