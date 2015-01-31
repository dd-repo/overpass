<?php
namespace Icecave\Overpass\Rpc\Api;

final class ApiCatalog implements ApiCatalogInterface
{
    /**
     * Make an API available for invocation.
     *
     * Any existing API with the same namespace and version will be replaced.
     *
     * @param ApiInterface $api The API.
     *
     * @throws LogicException if the server is already running.
     */
    public function expose(ApiInterface $api)
    {
        $version   = 'v' . $api->version()->major();
        $namespace = $api->namespace()->join($version);

        foreach ($api->procedures() as $procedure) {
            $name = strval(
                $namespace->join($procedure->name())
            );

            $this->procedures[$name] = $procedure;
        }
    }

    /**
     * Get the APIs that are a member of the catalog.
     *
     * @return mixed<ApiInterface> The APIs.
     */
    public function members()
    {
        return $this->members;
    }

    /**
     * Find the procedure appropriate for handling the given request.
     *
     * @param RequestInterface The request.
     *
     * @return callable|null The procedure that should handle the request, or null if no such procedure exists.
     */
    public function find(RequestInterface $request)
    {
        // $name = strval($request->name());

        // if (isset($this->procedures[$name])) {
        //     return $this->procedures[$name];
        // }

        // return null;
    }

    private $members = [];
    private $cache   = [];
}
