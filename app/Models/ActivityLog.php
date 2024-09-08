<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $table = 'activity_log';

    protected $fillable = [
        'log_name',
        'description',
        'subject_id',
        'subject_type',
        'causer_id',
        'causer_type',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Get the subject entity that the activity log refers to.
     *
     * @return MorphTo
     */
    public function subject(): MorphTo
    {
        return $this->morphTo('subject');
    }

    /**
     * Get the causer entity that triggered the activity log.
     *
     * @return MorphTo
     */
    public function causer(): MorphTo
    {
        return $this->morphTo('causer');
    }
}
