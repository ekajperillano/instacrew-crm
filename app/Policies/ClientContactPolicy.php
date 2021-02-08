<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientContactPolicy extends BasePolicy
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

    public function view_contact_list(User $user) {
        return $this->user($user)->can('view_contact_list');
    }

    public function view_contact_detail(User $user) {
        return $this->user($user)->can('view_contact_detail');
    }

    public function create_contact(User $user) {
        return $this->user($user)->can('create_contact');
    }

    public function update_contact(User $user) {
        return $this->user($user)->can('update_contact');
    }

    public function delete_contact(User $user) {
        return $this->user($user)->can('delete_contact');
    }   
}
