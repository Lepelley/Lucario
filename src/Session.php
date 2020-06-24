<?php

namespace Lucario;

class Session implements SessionInterface
{
    public function __construct()
    {
        $isStarted = false;
        if (php_sapi_name() !== 'cli') {
            $isStarted = (session_status() === PHP_SESSION_ACTIVE);
        }

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

    public function delete(string $name): self
    {
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
