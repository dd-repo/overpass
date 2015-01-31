<?php
namespace Icecave\Overpass\Name;

use Icecave\Repr\Repr;

/**
 * A qualified name, such as namespace name.
 */
final class QualifiedName implements NameInterface
{
    /**
     * @param string $name The name.
     */
    public function __construct($name)
    {
        if (
            !is_string($name)
            && !preg_match('/^[a-z][a-z\d]*(\.[a-z][a-z\d]*)*$/i', $name)
        ) {
            throw new InvalidArgumentException(
                sprintf(
                    'The value %s is not a valid qualified name.',
                    Repr::repr($name)
                );
            );
        }

        $this->name = $name;
    }

    /**
     * Adapt a value into a qualified name.
     *
     * @param NameInterface|string $name The name.
     *
     * @return QualifiedName
     * @throws InvalidArgumentException if the name is not valid.
     */
    public static function adapt($name)
    {
        if ($name instanceof QualifiedName) {
            return $name;
        } elseif ($name instanceof NameInterface) {
            $name = strval($name);
        }

        return new self($name);
    }

    /**
    * Join this qualfied name with the another name.
    *
    * @param NameInterface|string $name The name to join.
    *
    * @return QualifiedName
    */
    public function join($name)
    {
        return new self(
            $this->name . '.' . self::adapt($name)
        );
    }

    /**
     * Treating this name as a namespace, check if it contains the given name.
     *
     * @param NameInterface|string $name The name to check.
     *
     * @return boolean True if this name(space) contains the given name.
     */
    public function contains($name)
    {
        if (!is_string($name)) {
            $name = self::adapt($name);
        }

        return 0 === strpos($name, $this->name . '.');
    }

    /**
     * Get the name as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    private $name;
}
