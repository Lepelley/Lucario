<?php

namespace Lucario\Permission;

use Lucario\Entity\UserInterface;

interface PermissionDebuggerInterface
{
    /**
     * @param VoterInterface $voter
     * @param bool           $vote
     * @param string         $permission
     * @param UserInterface  $user
     * @param mixed|null     $subject
     *
     * @return void
     */
    public function debug(VoterInterface $voter, bool $vote, string $permission, UserInterface $user, $subject = null): void;
}