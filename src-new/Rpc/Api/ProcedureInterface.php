<?php
namespace Icecave\Overpass\Rpc\Api;

interface ProcedureInterface
{
    public function execute(RequestInterface $request, ResponseInterface $response);
}
