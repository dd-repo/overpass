<?php
namespace Icecave\Overpass\Serialization;

use LogicException;
use RuntimeException;
use Icecave\Isolator\IsolatorTrait;

/**
 * Defines a serialization encoding scheme.
 */
class GzipEncoding implements EncodingInterface
{
    /**
     * Encode the given buffer.
     *
     * @param string|null $encoding The encoding to use, or null to choose automatically.
     * @param string $buffer The buffer to encode.
     *
     * @return tuple<string, string|null> The encoded buffer, and the actual encoding use (null = none).
     */
    public function encode($scheme, $buffer)
    {
        if ($this->isEnabled === null) {
            $this->isEnabled = $this->isolator()->extension_loaded('zlib');
        }

        if ($scheme === null) {
            $scheme = 'gzip';
        }

        if ($this->isEnabled && $scheme === 'gzip') {
            return [gzcompress($buffer), 'gzip'];
        }

        return [$buffer, null];
    }

    /**
     * Decode the given buffer.
     *
     * @param string $encoding The encoding to use.
     * @param string $buffer The buffer to decode.
     *
     * @return string
     */
    public function decode($scheme, $buffer)
    {
        if ($this->isEnabled === null) {
            $this->isEnabled = $this->isolator()->extension_loaded('zlib');
        }

        if ($this->isEnabled && $scheme === 'gzip') {
            return gzuncompress($buffer);
        }

        throw new RuntimeException('Unsupported encoding scheme: ' . $scheme . '.');
    }

    use IsolatorTrait;

    private $isEnabled;
}
