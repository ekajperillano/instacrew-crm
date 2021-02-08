<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SocialPolicy extends BasePolicy
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

    public function view_social_list(User $user) {
        return $this->user($user)->can('view_social_list');
    }
    
    public function view_social_detail(User $user) {
        return $this->user($user)->can('view_social_detail');
    }
    
    public function create_social(User $user) {
        return $this->user($user)->can('create_social');
    }
    
    public function update_social(User $user) {
        return $this->user($user)->can('update_social');
    }
    
    public function delete_social(User $user) {
        return $this->user($user)->can('delete_social');
    }
    
}
