<?php
namespace App\Auth;

use App\Models\Role;
use Illuminate\Auth\SessionGuard as AuthSessionGuard;

/**
 * @method public isMaster()
 */
class SessionGuard extends AuthSessionGuard
{
    /**
     * Instruct current user is master
     *
     * @var Boolean
     */
    protected $isMaster;

    /**
     * Check current user is master
     *
     * @return boolean
     */
    public function isMaster()
    {
        if (is_bool($this->isMaster)) {
            return $this->isMaster;
        }

        $user = $this->user();

        if (! $user) {
            return false;
        }

        return $this->isMaster = $user->isMaster();
    }
}