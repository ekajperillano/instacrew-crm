<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy 
{
    private $user;
    
    /**
     * 
     */
    protected function user(User $user) {
        $this->user = $user;

        return $this;
    }

    protected function can($permission) {
        return $this->permissions()->contains($permission);
    }

    private function permissions() {
        if($this->user) {
            $this->user->load('role.permissions');
            return $this->user->role->permissions->pluck('code');
        }
        
        return [];
    }
}
