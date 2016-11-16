<?php

namespace User\TestAsset;

use Psr\Http\Message\StreamInterface;

class JsonStream implements StreamInterface
{
    /**
     * @var string
     */
    private $data;

    /**
     * JsonStream constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = json_encode($data);
    }

    public function __toString()
    {
        return $this->data;
    }

    public function close()
    {
    }

    public function detach()
    {
    }

    public function getSize()
    {
        return strlen($this->data);
    }

    public function tell()
    {
    }

    public function eof()
    {
    }

    public function isSeekable()
    {
        return false;
    }

    public function seek($offset, $whence = SEEK_SET)
    {
    }

    public function rewind()
    {
    }

    public function isWritable()
    {
        return false;
    }

    public function write($string)
    {
    }

    public function isReadable()
    {
        return true;
    }

    public function read($length)
    {
        // TODO: Implement read() method.
    }

    public function getContents()
    {
        return $this->data;
    }

    public function getMetadata($key = null)
    {
    }
}
