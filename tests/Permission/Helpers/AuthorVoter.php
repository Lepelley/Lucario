<?php

namespace Lucario\Tests\Permission\Helpers;

use Lucario\Entity\UserInterface;
use Lucario\Permission\VoterInterface;

class AuthorVoter implements VoterInterface
{

    const EDIT = 'edit_post';

    /**
     * @inheritDoc
     */
    public function canVote(string $permission, $subject = null): bool
    {
        return $permission === self::EDIT && $subject instanceof TestPost;
    }

    /**
     * @inheritDoc
     */
    public function vote(UserInterface $user, string $permission, $subject = null): bool
    {
        if (!$subject instanceof TestPost) {
            throw new \RuntimeException(sprintf(
                'Subject must be an instance of %s (Actual : %s)',
                TestPost::class,
                get_class($subject)
            ));
        }
        return $subject->getUser() === $user;
    }
}
