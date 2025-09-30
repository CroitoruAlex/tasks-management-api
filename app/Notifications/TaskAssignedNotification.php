<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('You have been assigned a new task')
            ->greeting("Hello {$notifiable->name},")
            ->line("You have been assigned to the task: **{$this->task->title}**.")
            ->line("Description: {$this->task->description}")
            ->line("Due date: {$this->task->due_date}")
            ->line('Please log in to check your task details.');
    }
}
