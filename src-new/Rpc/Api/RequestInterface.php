<?php
namespace Icecave\Overpass\Rpc\Api;

use Icecave\Overpass\Name\QualifiedName;

interface RequestInterface
{
    /**
     * Get the identity of the RPC client.
     *
     * @return IdentityInterface The RPC client's identity.
     */
    public function identity();

    /**
     * Get the name of the procedure being invoked.
     *
     * @return QualifiedName The name of the procedure being invoked.
     */
    public function name();

    /**
     * Get the arguments to the procedure.
     *
     * @return array The arguments.
     */
    public function arguments();

    /**
     * Indicates whether or not the client is waiting for a response.
     *
     * The procedure may avoid the generation of costly responses where the
     * client is not interested in the result.
     *
     * @return boolean True if the client is expecting a response; otherwise, false.
     */
    public function isAttached();

    /**
     * Get a human-readable string representation of the request.
     *
     * @return string
     */
    public function __toString();
}
