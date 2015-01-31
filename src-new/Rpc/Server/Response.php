<?php
namespace Icecave\Overpass\Rpc\Server;

use Crell\ApiProblem\ApiProblem;
use Icecave\Overpass\Rpc\Api\RequestInterface;
use Icecave\Overpass\Rpc\Api\ResponseInterface;
use Icecave\Repr\Repr;
use LogicException;

/**
 * An output implementation that sends the response to the caller immediately.
 */
class AttachedResponse extends ResponseInterface
{
    public function __construct(
        DriverInterface $driver,
        RequestInterface $request
    ) {
        $this->driver  = $driver;
        $this->request = $request;
    }

    /**
     * Complete the RPC request and send a result to the client.
     *
     * @param mixed $value The value to send.
     *
     * @throws LogicException if the response has already been fulfilled.
     */
    public function done($value = null)
    {
        if (null !== $this->result) {
            throw new LogicException('An RPC response has already been created.');
        }

        $this->result = [$value, null];

        if ($this->request->isAttached()) {
            $this->driver->done($this->request, $value);
        }

        $this->driver  = null;
        $this->request = null;
    }

    /**
     * Complete the RPC request and inform the client of an error condition.
     *
     * @param ApiProblem $error The error.
     *
     * @throws LogicException if the response has already been fulfilled.
     */
    public function fail(ApiProblem $error)
    {
        if (null !== $this->result) {
            throw new LogicException('An RPC response has already been created.');
        }

        $this->result = [null, $error];

        if ($this->request->isAttached()) {
            $this->driver->fail($this->request, $value);
        }

        $this->driver  = null;
        $this->request = null;
    }

    /**
     * Instruct the client to extend its timeout.
     *
     * This method can be used in long-running RPC calls to inform the client
     * that the server is still processing the request.
     *
     * @throws LogicException if the response has already been fulfilled.
     */
    public function extend()
    {
        if (null !== $this->result) {
            throw new LogicException('An RPC response has already been created.');
        }

        if ($this->request->isAttached()) {
            $this->driver->extend($this->request);
        }
    }

    /**
     * Get a human-readable string representation of the response.
     *
     * @return string
     */
    public function __toString()
    {
        list($value, $error) = $this->result;

        if ($error) {
            return $error->getTitle();
        }

        return Repr::repr($value);
    }

    private $driver;
    private $request;
    private $result;
}
