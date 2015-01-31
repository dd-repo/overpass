<?php
namespace Icecave\Overpass\Rpc\Api;

use Icecave\Overpass\Name\QualifiedName;
use Icecave\SemVer\Version;
use LogicException;

/**
 * An API is a set of publically exposed procedures that may be invoked by an
 * RPC client.
 */
interface ApiInterface
{
    /**
     * Get the API namespace.
     *
     * @return QualfiedName The API namespace.
     */
    public function namespace();

    /**
     * Get the API version.
     *
     * @return Version The API version.
     */
    public function version();

    /**
     * Get the procedures exposed by this API.
     *
     * @return array<ProcedureInterface> The procedures exposed by this API.
     */
    public function procedures();
}
