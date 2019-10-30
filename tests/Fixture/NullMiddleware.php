<?php
namespace Keepper\HttpHandling\Tests\Fixture;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NullMiddleware extends \Keepper\HttpHandling\NullMiddleware {

	public $isCalled = false;

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		$this->isCalled = true;
		return parent::process($request, $handler);
	}
}