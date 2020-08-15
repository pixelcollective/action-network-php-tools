<?php

namespace TinyPixel\ActionNetwork\Traits;

/**
 * Container trait
 */
trait Container
{
    /**
     * Get
     *
     * @param  string $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Set
     *
     * @param  string $id
     * @return void
     */
    public function set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * Has
     *
     * @param  string $id
     * @return bool
     */
    public function has($id)
    {
        return $this->offsetExists($id);
    }

        /**
     * Sets a parameter or an object.
     *
     * @param  string $id
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($id, $value): void
    {
        $this->values[$id] = $value;
    }

    /**
     * Gets a parameter or an object.
     *
     * @param  string $id
     * @return mixed
     */
    public function offsetGet($id)
    {
        if (!array_key_exists($id, $this->values)) {
            throw new \InvalidArgumentException(
                sprintf('Identifier "%s" is not defined.', $id)
            );
        }

        $isFactory = is_object($this->values[$id])
            && method_exists($this->values[$id], '__invoke');

        return $isFactory
            ? $this->values[$id]($this)
            : $this->values[$id];
    }

    /**
     * Checks if a parameter or an object is set.
     *
     * @param  string $id The unique identifier for the parameter or object
     * @return Boolean
     */
    public function offsetExists($id): bool
    {
        return array_key_exists($id, $this->values);
    }

    /**
     * Unsets a parameter or an object.
     *
     * @param string $id
     */
    public function offsetUnset($id): void
    {
        unset($this->values[$id]);
    }
}
