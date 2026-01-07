<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Ticket extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'priority', 'type', 'assigned_to', 'due_date', 'title', 'description'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'type',
        'is_active',
        'assigned_to',
        'due_date',
    ];

    protected $casts = [
        'status' => \App\Enums\TicketStatus::class,
        'priority' => \App\Enums\TicketPriority::class,
        'type' => \App\Enums\TicketType::class,
        'is_active' => 'boolean',
        'due_date' => 'date',
    ];

    public function responsible(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
