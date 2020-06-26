<?php

namespace Lucario\Session;

class Session implements SessionInterface
{
    public function __construct()
    {
        $isStarted = ('cli' !== php_sapi_name()) ? (session_status() === PHP_SESSION_ACTIVE) : false;

        if (false === $isStarted) {
            session_start();
        }
    }


    public function get(string $name)
    {
        return array_key_exists($name, $_SESSION) ? $_SESSION[$name] : null;
    }

    /**
     * @param string     $name
     * @param mixed|null $value
     *
     * @return self
     */
    public function set(string $name, $value = null): self
    {
        $_SESSION[$name] = $value;

        return $this;
    }

    public function delete(?string $name = null): self
    {
        if (null === $name) {
            session_destroy();
            session_start();

            return $this;
        }
        if (array_key_exists($name, $_SESSION)) {
            unset($_SESSION[$name]);
        }

        return $this;
    }

    public function getAll(): array
    {
        return $_SESSION;
    }
}
