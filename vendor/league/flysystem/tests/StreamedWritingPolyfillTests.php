<?php

namespace League\Flysystem\Adapter;

use League\Flysystem\Config;
use League\Flysystem\Stub\StreamedWritingStub;
use PHPUnit\Framework\TestCase;

class StreamedWritingPolyfillTests extends TestCase
{
    use \PHPUnitHacks;

    public function testWrite()
    {
        $stream = tmpfile();
        fwrite($stream, 'contents');
        $stub = new StreamedWritingStub();
        $response = $stub->writeStream('path.txt', $stream, new Config());
        $this->assertEquals(['path' => 'path.txt', 'contents' => 'contents'], $response);
        fclose($stream);
    }

    public function testUpdate()
    {
        $stream = tmpfile();
        fwrite($stream, 'contents');
        $stub = new StreamedWritingStub();
        $response = $stub->updateStream('path.txt', $stream, new Config());
        $this->assertEquals(['path' => 'path.txt', 'contents' => 'contents'], $response);
        fclose($stream);
    }
}
