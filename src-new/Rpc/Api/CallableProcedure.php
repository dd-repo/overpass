<?php
namespace Icecave\Overpass\Rpc\Api;

use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;

class CallableProcedure implements ProcedureInterface
{
    public function __construct(callable $callable)
    {
        $this->callable  = $callable;
    }

    public function execute(RequestInterface $request, ResponseInterface $response)
    {
    }

    private function reflector(callable $procedure)
    {
        // The implementation is a string representing a static method ...
        if (is_string($procedure) && $pos = strpos($procedure, '::')) {
            return new ReflectionMethod(
                substr($procedure, 0, $pos),
                substr($procedure, $pos + 2)
            );

        // The implementation is an array representing a method ...
        } elseif (is_array($procedure)) {
            list($classOrObject, $method) = $procedure;

            return new ReflectionMethod(
                $classOrObject,
                $method
            );

        // The implementation is a global function, callable object, etc ...
        } else {
            return new ReflectionFunction($procedure);
        }
    }

    private $callable;
    private $reflector;
}
