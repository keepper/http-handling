<?php

namespace Keepper\HttpHandling\Tests;

use Keepper\HttpHandling\DecoratingRequestHandler;
use Keepper\HttpHandling\Tests\Fixture\NullMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DecoratingRequestHandlerTest extends TestCase {

	public function testHandler() {
		$request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
		$expectedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();

		$handler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
		$handler->expects($this->once())->method('handle')->with($request)->willReturn($expectedResponse);

		$middleware = new NullMiddleware();
		$QueueHandler = new DecoratingRequestHandler($middleware, $handler);

		$response = $QueueHandler->handle($request);

		$this->assertEquals($expectedResponse, $response);
		$this->assertTrue($middleware->isCalled);
	}
}