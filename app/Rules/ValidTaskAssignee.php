<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class ValidTaskAssignee implements Rule
{
    protected ?User $user = null;
    protected string $message = 'Invalid task assignee.';

    public function passes($attribute, $value): bool
    {
        $this->user = User::find($value);

        if (!$this->user) {
            $this->message = 'The selected user does not exist.';

            return false;
        }

        // Only allow users with 'user' role
        if ($this->user->role !== 'user') {
            $this->message = 'Only users with the "user" role can be assigned to tasks.';

            return false;
        }

        return true;
    }

    public function message(): string
    {
        return $this->message;
    }
}
