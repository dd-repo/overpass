<?php
namespace Icecave\Overpass\Rpc\Server;

use Icecave\Overpass\Rpc\Api\RequestInterface;
use Icecave\Overpass\Identity\IdentityInterface;
use Icecave\Overpass\Name\QualifiedName;

/**
 * Represents an RPC request.
 */
class Request implements RequestInterface
{
    /**
     * @param string            $id         The request ID.
     * @param IdentityInterface $identity   The RPC client's identity.
     * @param QualifiedName     $name       The name of the procedure being invoked.
     * @param array             $arguments  The arguments.
     * @param integer|float     $timeout    The client's timeout value, in seconds.
     * @param boolean           $isAttached True if the client is expecting a response; otherwise, false.
     */
    public function __construct(
        $id,
        IdentityInterface $identity,
        QualifiedName $name,
        array $arguments,
        $timeout,
        $isAttached
    ) {
        $this->id         = $id;
        $this->identity   = $identity;
        $this->name       = $name;
        $this->arguments  = $arguments;
        $this->timeout    = $timeout;
        $this->isAttached = $isAttached;
    }

    /**
     * Get the request ID.
     *
     * @return string The request ID.
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Get the identity of the RPC client.
     *
     * @return IdentityInterface The RPC client's identity.
     */
    public function identity()
    {
        return $this->identity;
    }

    /**
     * Get the name of the procedure being invoked.
     *
     * @return QualifiedName The name of the procedure being invoked.
     */
    public function name();
    {
        return $this->name;
    }

    /**
     * Get the arguments to the procedure.
     *
     * @return array The arguments.
     */
    public function arguments()
    {
        return $this->arguments;
    }

    /**
     * Indicates whether or not the client is waiting for a response.
     *
     * The procedure may avoid the generation of costly responses where the
     * client is not interested in the result.
     *
     * @return boolean True if the client is expecting a response; otherwise, false.
     */
    public function isAttached()
    {
        return $this->isAttached;
    }

    /**
     * Get a human-readable string representation of the request.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s %s %s',
            $this->id,
            $this->identity,
            $this->name
        );
    }

    private $id;
    private $identity;
    private $name;
    private $arguments;
    private $isAttached;
}
