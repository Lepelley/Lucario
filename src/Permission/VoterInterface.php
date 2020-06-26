<?php

namespace Lucario\Permission;

use Lucario\Entity\UserInterface;

interface VoterInterface
{
    /**
     * @param string $permission
     * @param mixed  $subject
     *
     * @return bool
     */
    public function canVote(string $permission, $subject = null): bool;

    /**
     * Represents his vote
     *
     * @param UserInterface $user
     * @param string        $permission
     * @param mixed         $subject
     *
     * @return bool
     */
    public function vote(UserInterface $user, string $permission, $subject = null): bool;
}
