<?php

namespace Lucario\Tests\Permission\Helpers;

use Lucario\Entity\UserInterface;
use Lucario\Permission\VoterInterface;

class AlwaysNoVoter implements VoterInterface
{

    public function canVote(string $permission, $subject = null): bool
    {
        return true;
    }

    public function vote(UserInterface $user, string $permission, $subject = null): bool
    {
        return false;
    }
}
