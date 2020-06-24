<?php

namespace Lucario;

interface SessionInterface
{

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function get(string $name);

    /**
     * @param string     $name
     * @param mixed|null $value
     *
     * @return self
     */
    public function set(string $name, $value = null): self;

    public function delete(string $name): self;

    public function getAll(): array;
}
