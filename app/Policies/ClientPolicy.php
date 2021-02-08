<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy extends BasePolicy
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

    public function view_client_list(User $user) {
        return $this->user($user)->can('view_client_list');
    }

    public function view_client_detail(User $user) {
        return  $this->user($user)->can('view_client_detail');
    }

    public function create_client(User $user) {
        return  $this->user($user)->can('create_client');
    }

    public function update_client(User $user) {
        return $this->user($user)->can('update_client');
    }

    public function delete_client(User $user) {
        return $this->user($user)->can('delete_client');
    }

    public function update_client_status(User $user) {
        return  $this->user($user)->can('update_client_status');
    }

    public function update_client_type(User $user) {
        return $this->user($user)->can('update_client_type');
    }
}
