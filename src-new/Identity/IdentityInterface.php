<?php
namespace Icecave\Overpass\Identity;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * Represents the identity of an Overpass client.
 */
interface IdentityInterface
{
    /**
     * Determines whether or not two identities are equivalent.
     *
     * @param IdentityInterface $identity The identity to compare.
     *
     * @return boolean True if this identity is the same as the given one; otherwise, false.
     */
    public function is(IdentityInterface $identity);

    /**
     * Get a human-readable string representation of the identity.
     *
     * @return string
     */
    public function __toString();
}
