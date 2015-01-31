<?php
namespace Icecave\Overpass\Rpc\Server;

use Crell\ApiProblem\ApiProblem;
use Icecave\Overpass\Rpc\Api\RequestInterface;
use LogicException;

interface DriverInterface
{
    /**
     * Inform the driver that the server will not process any more requests.
     *
     * @param ApiCatalog $catalog The API catalog being handled.
     */
    public function initialize(ApiCatalog $catalog);

    /**
     * Inform the driver that the server will not process any more requests.
     */
    public function shutdown();

    /**
     * Wait for the next RPC request.
     *
     * @param integer|float $timeout The number of seconds to wait for a request.
     *
     * @return RequestInterface|null A tuple of the RPC request and response.
     */
    public function wait($timeout);

    /**
     * Agree to handle the given request.
     *
     * @param RequestInterface $request The request.
     */
    public function accept(RequestInterface $request);

    /**
     * Reject the given request.
     *
     * @param RequestInterface $request The request.
     */
    public function reject(RequestInterface $request);

    /**
     * Respond to a previous API request with a successful result.
     *
     * @param RequestInterface  $request  The request being responded to.
     * @param ResponseInterface $response The response to send.
     * @param mixed $value The value to return.
     *
     * @throws LogicException if the request has already been responded to.
     * @throws LogicException if the client is not expecting a response.
     */
    public function done(RequestInterface $request, $value);

    /**
     * Respond to a previous API request with an error condition.
     *
     * @param RequestInterface  $request  The request being responded to.
     * @param ResponseInterface $response The response to send.
     * @param ApiProblem $error The error that occurred.
     *
     * @throws LogicException if the request has already been responded to.
     * @throws LogicException if the client is not expecting a response.
     */
    public function fail(RequestInterface $request, ApiProblem $error);

    /**
     * Extend the caller's timeout.
     *
     * @param RequestInterface  $request  The request.
     *
     * @throws LogicException if the request has already been responded to.
     * @throws LogicException if the client is not expecting a response.
     */
    public function extend(RequestInterface $request);
}
