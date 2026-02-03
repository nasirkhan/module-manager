<?php

declare(strict_types=1);

namespace Dotenv\Repository;

interface RepositoryInterface
{
    /**
     * Determine if the given environment variable is defined.
     *
     * @param  string  $name
     * @return bool
     */
    public function has(string $name);

    /**
     * Get an environment variable.
     *
     * @param  string  $name
     * @return string|null
     *
     * @throws \InvalidArgumentException
     */
    public function get(string $name);

    /**
     * Set an environment variable.
     *
     * @param  string  $name
     * @param  string  $value
     * @return bool
     *
     * @throws \InvalidArgumentException
     */
    public function set(string $name, string $value);

    /**
     * Clear an environment variable.
     *
     * @param  string  $name
     * @return bool
     *
     * @throws \InvalidArgumentException
     */
    public function clear(string $name);
}
