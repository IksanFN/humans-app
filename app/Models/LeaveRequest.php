<?php

namespace App\Models;

use App\Enums\LeaveRequestStatus;
use App\Enums\LeaveRequestType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'type' => LeaveRequestType::class,
        'status' => LeaveRequestStatus::class,
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approve(): void
    {
        $this->status = LeaveRequestStatus::APPROVED;
        $this->save();
    }

    public function reject(): void
    {
        $this->status = LeaveRequestStatus::REJECTED;
        $this->save();
    }
}
