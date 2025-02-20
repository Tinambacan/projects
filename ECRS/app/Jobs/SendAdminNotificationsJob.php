<?php

namespace App\Jobs;

use App\Models\Admin;
use App\Notifications\EmailNotificationAdminIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendAdminNotificationsJob implements ShouldQueue 
{
    use Queueable;

    public function handle()
    {
        $admins = Admin::where('branch', 1)->get();

        $data = "Hello";

        foreach ($admins as $admin) {
            $login = $admin->login()->first();

            if ($login && $login->email) {
                Notification::route('mail', $login->email)->notify(
                    new EmailNotificationAdminIntegration($data)
                );
            }
        }
    }
}

