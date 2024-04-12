<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Untuk handle inputan Amount karena menggunakan RawJS
    public function amount(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => str($value)->replace(',', '')
        );
    }
}
