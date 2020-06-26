<?php

namespace Lucario\Permission;

use Lucario\Entity\UserInterface;

final class Permission
{
    /**
     * @var VoterInterface[]
     */
    private array $voters;

    /**
     * @var PermissionDebuggerInterface|null
     */
    private ?PermissionDebuggerInterface $debugger;

    public function __construct(?PermissionDebuggerInterface $debugger = null)
    {
        $this->voters = [];
        $this->debugger = $debugger;
    }

    /**
     * Return if a user can access a subject/an action.
     *
     * @param UserInterface $user
     * @param string        $permission
     * @param mixed         $subject
     *
     * @return bool
     */
    public function can(UserInterface $user, string $permission, $subject = null): bool
    {
        foreach ($this->voters as $voter) {
            if ($voter->canVote($permission, $subject)) {
                $vote = $voter->vote($user, $permission, $subject);
                if ($this->debugger) {
                    $this->debugger->debug($voter, $vote, $permission, $user, $subject);
                }

                if (true === $vote) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param VoterInterface $voter
     *
     * @return self
     */
    public function addVoter(VoterInterface $voter): self
    {
        $this->voters[] = $voter;

        return $this;
    }
}
