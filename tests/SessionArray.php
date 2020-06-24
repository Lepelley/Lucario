<?php

namespace Lucario\Tests;

use Lucario\SessionInterface;

class SessionArray implements SessionInterface
{
    private array $session;

    public function __construct()
    {
        $this->session = [];
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function get(string $name)
    {
        return array_key_exists($name, $this->session) ? $this->session[$name] : null;
    }

    public function set(string $name, $value = null): self
    {
        $this->session[$name] = $value;

        return $this;
    }

    public function delete(?string $name = null): self
    {
        if (null === $name) {
            unset($this->session);
            $this->session = [];

            return $this;
        }

        if (array_key_exists($name, $this->session)) {
            unset($this->session[$name]);
        }

        return $this;
    }

    public function getAll(): array
    {
        return $this->session;
    }
}