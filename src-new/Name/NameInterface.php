<?php
namespace Icecave\Overpass\Name;

/**
 * A valid RPC procedure or Pub/Sub topic name.
 */
interface NameInterface
{
    /**
     * Get the name as a string.
     *
     * @return string
     */
    public function __toString();
}
