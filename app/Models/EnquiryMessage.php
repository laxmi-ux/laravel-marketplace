<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnquiryMessage extends Model
{
    protected $fillable = [
        'requirement_id',
        'rfq_id',
        'sender_id',
        'receiver_id',
        'message',
    ];

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(
            Requirement::class,
            'requirement_id'
        );
    }

    public function rfq(): BelongsTo
    {
        return $this->belongsTo(
            Rfq::class,
            'rfq_id'
        );
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'sender_id'
        );
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'receiver_id'
        );
    }
}