<?php
namespace Icecave\Overpass\Rpc\Api;

use Crell\ApiProblem\ApiProblem;
use LogicException;

/**
 * Provide a response to an RPC request.
 */
interface ResponseInterface
{
    /**
     * Complete the RPC request and send a result to the client.
     *
     * @param mixed $value The value to send.
     *
     * @throws LogicException if the response has already been fulfilled.
     */
    public function done($value = null);

    /**
     * Complete the RPC request and inform the client of an error condition.
     *
     * @param ApiProblem $error The error.
     *
     * @throws LogicException if the response has already been fulfilled.
     */
    public function fail(ApiProblem $error);

    /**
     * Instruct the client to extend its timeout.
     *
     * This method can be used in long-running RPC calls to inform the client
     * that the server is still processing the request.
     *
     * @throws LogicException if the response has already been fulfilled.
     */
    public function extend();

    /**
     * Get a human-readable string representation of the response.
     *
     * @return string
     */
    public function __toString();
}
