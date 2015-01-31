<?php
namespace Icecave\Overpass\Rpc\Error;

interface ErrorFactoryInterface
{
    public function createInternalError();

    public function createTimeoutError();
}
