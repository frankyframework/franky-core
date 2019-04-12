<?php
use PHPUnit\Framework\TestCase;
use Franky\Core\request;

class RequestTest extends TestCase
{
    public function testGet()
    {
          $request = new request();

          $this->assertSame($request->link('http://www.url.com/path'), 'http://www.url.com/path');
          return $data;
    }
}
