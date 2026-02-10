<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'target_user_id',
        'action'
    ];

    public function admin()
    {
        // Includes all data (active and deleted)
        return $this->belongsTo(User::class, 'admin_id')->withTrashed();
    }

    public function target()
    {
        // Includes all data (active and deleted)
        return $this->belongsTo(User::class, 'target_user_id')->withTrashed();
    }
}
