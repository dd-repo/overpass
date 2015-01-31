<?php
namespace Icecave\Overpass\Rpc\Server;

use Crell\ApiProblem\ApiProblem;

class Executor implements ExecutorInterface
{
    public function execute(
        ProcedureInterface $procedure,
        RequestInterface $request,
        ResponseInterface $response
    ) {
        try {
            $result = $procedure->execute($request, $response);
        } catch (RpcExceptionInterface $e) {
            throw $e;
        } catch (Exception $e) {
            if (!$response->isFulfilled()) {
                $error = $this
                    ->errorFactory
                    ->createFromException($e);

                $response->fail($error);
            }

            throw $e;
        }

        if ($response->isFulfilled()) {
            return;
        }

        if ($result instanceof ApiProblem) {
            $response->fail($result);
        } else {
            $response->done($result);
        }
    }

    private $errorFactory;
}
