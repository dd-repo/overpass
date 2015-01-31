<?php
namespace Icecave\Overpass\Identity;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * Represents an unknown identity.
 */
class Anonymous implements IdentityInterface
{
    /**
     * Determines whether or not two identities are equivalent.
     *
     * This implementation always returns false as there is no guarantee that
     * two anonymous users are the SAME anonymous user.
     *
     * @param IdentityInterface $identity The identity to compare.
     *
     * @return boolean True if this identity is the same as the given one; otherwise, false.
     */
    public function is(IdentityInterface $identity)
    {
        return false;
    }

    /**
     * Get a human-readable string representation of the identity.
     *
     * @return string
     */
    public function __toString()
    {
        return 'anonymous';
    }
}
