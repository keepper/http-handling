<?php

namespace Keepper\HttpHandling;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DecoratingRequestHandler implements RequestHandlerInterface
{

	/**
	 * @var MiddlewareInterface
	 */
	private $middleware;

	/**
	 * @var RequestHandlerInterface
	 */
	private $nextHandler;

	public function __construct(MiddlewareInterface $middleware, RequestHandlerInterface $nextHandler)
	{
		$this->middleware = $middleware;
		$this->nextHandler = $nextHandler;
	}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		return $this->middleware->process($request, $this->nextHandler);
	}
}