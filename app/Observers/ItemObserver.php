<?php

namespace App\Observers;

use App\Enums\UserRole;
use App\Models\Item;
use App\Models\User;
use App\Notifications\Api\NewItemNotification;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ItemObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Item "created" event.
     */
    public function created(Item $item): void
    {
        $admins = User::whereIn('role', [UserRole::ADMIN, UserRole::SUPER_ADMIN])->get();
        $user = Auth::user();
        foreach ($admins as $admin) {
            Notification::send($admin, new NewItemNotification($item, $user));
        }
    }

    /**
     * Handle the Item "deleted" event.
     */
    public function deleted(Item $item): void
    {
        //
    }

    /**
     * Handle the Item "restored" event.
     */
    public function restored(Item $item): void
    {
        //
    }

    /**
     * Handle the Item "force deleted" event.
     */
    public function forceDeleted(Item $item): void
    {
        //
    }
}
