<?php
namespace Keepper\HttpHandling;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NullMiddleware implements MiddlewareInterface {

	/**
	 * @var ResponseInterface|null
	 */
	private $response;

	public function __construct(ResponseInterface $response = null) {
		$this->response = $response;
	}

	/**
	 * @inheritdoc
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		if ( !is_null($this->response) ) {
			return $this->response;
		}

		return $handler->handle($request);
	}
}