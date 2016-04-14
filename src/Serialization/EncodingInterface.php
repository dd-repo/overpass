<?php
namespace Icecave\Overpass\Serialization;

/**
 * Defines a serialization encoding scheme.
 */
interface EncodingInterface
{
    /**
     * Encode the given buffer.
     *
     * @param string|null $encoding The encoding to use, or , or null to choose automatically.
     * @param string $buffer The buffer to encode.
     *
     * @return tuple<string, string|null> The encoded buffer, and the actual encoding use (null = none).
     */
    public function encode($scheme, $buffer);

    /**
     * Decode the given buffer.
     *
     * @param string $encoding The encoding to use.
     * @param string $buffer The buffer to decode.
     *
     * @return string
     */
    public function decode($scheme, $buffer);
}
