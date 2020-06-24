<?php

namespace Lucario\Tests\Repository;

use Lucario\Entity\AbstractEntity;

class User extends AbstractEntity
{
    /**
     * @var int|string|null
     */
    private $id = null;
    private ?string $name = null;

    /**
     * @return int|string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|string|null $id
     *
     * @return User
     */
    public function setId($id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return User
     */
    public function setName(?string $name): User
    {
        $this->name = $name;
        return $this;
    }


}
