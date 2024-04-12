<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class EmployeeObserver
{
    public function created(Employee $employee)
    {
        $recipient = auth()->user();
        Notification::make()
            ->success()
            ->title('Employee Created')
            ->body("{$employee->name} has been created")
            ->sendToDatabase($recipient);
    }
}