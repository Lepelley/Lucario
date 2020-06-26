<?php

namespace Lucario\Tests\Permission\Helpers;

use Lucario\Entity\AbstractEntity;
use Lucario\Entity\UserInterface;

class TestPost extends AbstractEntity
{
    private UserInterface $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
