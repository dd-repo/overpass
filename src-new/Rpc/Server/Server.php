<?php
namespace Icecave\Overpass\Rpc\Server;

use Icecave\Overpass\Rpc\Api\ApiCatalogInterface;
use Icecave\Overpass\Rpc\Api\RequestInterface;

/**
 * Accepts incoming RPC requests and dispatches them to a handler.
 */
final class Server implements ServerInterface
{
    public function __construct(
        ProcedureCatalogInterface $catalog,
        ExecutorInterface $executor,
        DriverInterface $driver,
        LoggerInterface $logger
    ) {
        $this->catalog    = $catalog;
        $this->executor   = $executor;
        $this->driver     = $driver;
        $this->logger     = $logger;
        $this->isRunning  = false;
        $this->isStopping = false;
    }

    /**
     * Run the RPC server.
     *
     * @throws LogicException if the server is already running.
     */
    public function run()
    {
        if ($this->isRunning) {
            throw new LogicException('The server is already running.');
        }

        $this->isRunning  = true;
        $this->isStopping = false;

        try {
            $this->logger->info('Server starting');
            $this->logApiVersions();
            $this->driver->initialize($this->catalog);
            $this->logger->info('Server started successfully');
            $this->process();
            $this->driver->shutdown();
            $this->logger->info('Server shutdown gracefully');
        } catch (Exception $e) {
            $this->logger->critical(
                'Server shutdown unexpectedly: {exception}',
                ['exception' => $e]
            );

            throw $e;
        } finally {
            $this->isRunning  = false;
            $this->isStopping = false;
        }
    }

    /**
     * Stop the RPC server.
     */
    public function stop()
    {
        if ($this->isRunning) {
            $this->isStopping = true;
            $this->logger->info('Server stopping');
        }
    }

    /**
     * Produce log messages describing the exposed APIs and their versions.
     */
    private function logApiVersions()
    {
        foreach ($this->catalog->members() as $api) {
            $this->logger->info(
                'Exposed API {namespace} version {version}',
                [
                    'namespace' => strval($api->namespace()),
                    'version'   => strval($api->version()),
                ]
            );
        }
    }

    /**
     * Process requests until instructed to stop.
     */
    private function process()
    {
        while (!$this->isStopping) {
            $request = $this->driver->wait($this->timeout);

            if ($request) {
                $this->dispatch($request);
            }
        }
    }

    /**
     * Dispatch an incoming request.
     *
     * @param RequestInterface $request The request.
     */
    private function dispatch(RequestInterface $request)
    {
        $procedure = $this->catalog->find($request);

        if (null === $procedure) {
            $this->driver->reject($request);

            $this->logger->error(
                '{request}: unknown procedure',
                ['request'  => strval($request)]
            );

            return;
        }

        $response = new Response(
            $this->driver,
            $request
        );

        $this->driver->accept($request);

        $this->executor->execute(
            $procedure,
            $request,
            $response
        );

        $this->logger->error(
            '{request}: {response}',
            [
                'request'  => strval($request),
                'response' => strval($response),
            ]
        );
    }

    private $catalog;
    private $executor;
    private $driver;
    private $logger;
    private $isRunning;
    private $isStopping;
}
