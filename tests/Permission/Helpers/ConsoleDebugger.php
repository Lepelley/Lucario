<?php

namespace Lucario\Tests\Permission\Helpers;

use Lucario\Entity\UserInterface;
use Lucario\Permission\PermissionDebuggerInterface;
use Lucario\Permission\VoterInterface;

final class ConsoleDebugger implements PermissionDebuggerInterface
{

    /**
     * @inheritDoc
     */
    public function debug(VoterInterface $voter, bool $vote, string $permission, UserInterface $user, $subject = null): void
    {
        $className = get_class($voter);
        $vote = $vote ? "\e[32myes\e[0m" : "\e[31mno\e[0m";
        file_put_contents('php://stdout', "\e[34m$className\e[0m : \e[37m$vote on $permission\n");
    }
}
