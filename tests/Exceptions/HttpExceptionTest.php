<?php

namespace Mosaic\Routing\Tests\Exceptions;

use Mosaic\Routing\Exceptions\HttpException;

class HttpExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function test_can_get_status_code_of_a_http_exception()
    {
        $e = new HttpException('Not found', 404);

        $this->assertEquals('Not found', $e->getMessage());
        $this->assertEquals(404, $e->getStatusCode());
    }
}
