<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'old_values',
        'new_values',
        'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        if (!$this->subject_type || !$this->subject_id) {
            return null;
        }
        return $this->subject_type::find($this->subject_id);
    }

    /**
     * Get a human-readable summary of changes (for "updated" actions).
     */
    public function getChangesSummary(): array
    {
        if ($this->action !== 'updated' || !is_array($this->old_values) || !is_array($this->new_values)) {
            return [];
        }
        $summary = [];
        $ignore = ['updated_at', 'created_at'];
        foreach ($this->new_values as $key => $new) {
            if (in_array($key, $ignore)) {
                continue;
            }
            $old = $this->old_values[$key] ?? null;
            if ($old !== $new) {
                $summary[$key] = ['old' => $old, 'new' => $new];
            }
        }
        return $summary;
    }

    /**
     * Log an activity (helper).
     */
    public static function log(string $action, string $subjectType, ?int $subjectId = null, ?array $oldValues = null, ?array $newValues = null, ?string $description = null): self
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'description' => $description,
        ]);
    }
}
