<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function update(User $login,User $user)
    {
        return $login->id === $user->id;
    }
    
    public function destroy(User $login,User $user){
    	return $login->is_admin && $login->id !==$user->id;
    }
    
    public function follow(User $currentUser, User $user)
    {
    	return $currentUser->id !== $user->id;
    }
}
