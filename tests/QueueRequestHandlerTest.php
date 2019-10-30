<?php
namespace Keepper\HttpHandling\Tests;

use Keepper\HttpHandling\QueueRequestHandler;
use Keepper\HttpHandling\Tests\Fixture\NullMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class QueueRequestHandlerTest extends TestCase {

	public function testFailHandlerResponse() {
		$request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
		$expectedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
		$failHandler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
		$failHandler->expects($this->once())->method('handle')->with($request)->willReturn($expectedResponse);

		$QueueHandler = new QueueRequestHandler($failHandler);

		$response = $QueueHandler->handle($request);

		$this->assertEquals($expectedResponse, $response);
	}

	public function testStackedHandle() {
		$failHandler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
		$failHandler->expects($this->never())->method('handle');

		$request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
		$expectedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();

		$firstMiddleware = new NullMiddleware();
		$secondMiddleware = new NullMiddleware($expectedResponse);

		$QueueHandler = new QueueRequestHandler($failHandler);
		$QueueHandler->add($firstMiddleware);
		$QueueHandler->add($secondMiddleware);

		$response = $QueueHandler->handle($request);

		$this->assertEquals($expectedResponse, $response);
		$this->assertTrue($firstMiddleware->isCalled);
	}

	public function testDoubleCall() {
		$failHandler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
		$failHandler->expects($this->never())->method('handle');

		$request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
		$expectedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();

		$firstMiddleware = new NullMiddleware();
		$secondMiddleware = new NullMiddleware($expectedResponse);

		$QueueHandler = new QueueRequestHandler($failHandler);
		$QueueHandler->add($firstMiddleware);
		$QueueHandler->add($secondMiddleware);

		$responseFirst = $QueueHandler->handle($request);

		$this->assertEquals($expectedResponse, $responseFirst);
		$this->assertTrue($firstMiddleware->isCalled);

		$responseSecond = $QueueHandler->handle($request);
		$this->assertEquals($responseFirst, $responseSecond);
	}
}