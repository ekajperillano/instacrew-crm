<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view_user_list(User $user) {
        return $this->user($user)->can('view_user_list');
    }

    public function invite_user(User $user) {
        return $this->user($user)->can('invite_user');
    }

    public function update_user(User $user) {
        return $this->user($user)->can('update_user');
    }
}
