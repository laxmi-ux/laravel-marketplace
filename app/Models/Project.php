<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'listing_id',
        'project_type',
        'project_status',
        'developer_name',
        'total_area',
        'area_unit',
        'start_date',
        'completion_date',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'completion_date' => 'date',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}