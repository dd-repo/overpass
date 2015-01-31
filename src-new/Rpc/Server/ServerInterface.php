<?php
namespace Icecave\Overpass\Rpc\Server;

/**
 * Accepts incoming RPC requests and dispatches them to a handler.
 */
interface ServerInterface
{
    /**
     * Run the RPC server.
     *
     * @throws LogicException if the server is already running.
     */
    public function run();

    /**
     * Stop the RPC server.
     */
    public function stop();
}
