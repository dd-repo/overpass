<?php
namespace Icecave\Overpass\Name;

/**
 * A unqualified name, such as a procedure name.
 */
final class Name implements NameInterface
{
    /**
     * @param string $name The name.
     */
    public function __construct($name)
    {
        if (
            !is_string($name)
            && !preg_match('/^[a-z][a-z\d]*$/i', $name)
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
     * Adapt a value into a name.
     *
     * @param Name|string $name The name.
     *
     * @return Name
     * @throws InvalidArgumentException if the name is not valid.
     */
    public static function adapt($name)
    {
        if ($name instanceof Name) {
            return $name;
        }

        return new self($name);
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
