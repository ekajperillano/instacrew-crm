<?php

namespace  App\Notifications\Backup;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Spatie\Backup\Events\BackupWasSuccessful as BackupWasSuccessfulEvent;
use Spatie\Backup\Notifications\BaseNotification;

class BackupWasSuccessful extends BaseNotification
{
    /** @var \Spatie\Backup\Events\BackupWasSuccessful */
    protected $event;

    public function __construct(BackupWasSuccessfulEvent $event)
    {
        $this->event = $event;
    }

    public function toMail(): MailMessage
    {   
        return (new MailMessage)
            ->from(
                config('backup.notifications.mail.from.address', config('mail.from.address')),
                config('backup.notifications.mail.from.name', config('mail.from.name'))
            )
            ->subject(
                trans(
                    'backup::notifications.backup_successful_subject',
                    [
                        'application_name' => $this->applicationName()
                    ]
                )
            )
            ->line(
                trans(
                    'backup::notifications.backup_successful_body',
                    [
                        'application_name' => $this->applicationName(),
                        'disk_name' => $this->diskName()
                    ]
                )
            );
    }
}
