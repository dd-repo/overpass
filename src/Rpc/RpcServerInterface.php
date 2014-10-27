<?php
namespace Icecave\Overpass\Rpc;

use Psr\Log\LoggerAwareInterface;

interface RpcServerInterface extends LoggerAwareInterface
{
    /**
     * Expose a procedure.
     *
     * @param string   $name      The public name of the procedure.
     * @param callable $procedure The procedure implementation.
     *
     * @throws LogicException if the server is already running.
     */
    public function expose($name, callable $procedure);

    /**
     * Run the RPC server.
     */
    public function run();

    /**
     * Stop the RPC server.
     */
    public function stop();
}
