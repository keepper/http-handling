<?php
namespace Keepper\HttpHandling;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class QueueRequestHandler implements RequestHandlerInterface
{

	/**
	 * @var MiddlewareInterface[]
	 */
	private $middleware = [];

	/**
	 * @var RequestHandlerInterface
	 */
	private $fallbackHandler;

	private $currentIndex;

	public function __construct(RequestHandlerInterface $fallbackHandler)
	{
		$this->fallbackHandler = $fallbackHandler;
	}

	public function add(MiddlewareInterface $middleware)
	{
		$this->middleware[] = $middleware;
	}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		$rootCall = false;
		if ( is_null($this->currentIndex) ) {
			$rootCall = true;
			$this->currentIndex = 0;
		}

		// Last middleware in the queue has called on the request handler.
		if (count($this->middleware) <= $this->currentIndex) {
			$result = $this->fallbackHandler->handle($request);
		} else {
			$middleware = $this->middleware[$this->currentIndex];
			$this->currentIndex++;
			$result = $middleware->process($request, $this);
		}

		if ($rootCall) {
			$this->currentIndex = null;
		}

		return $result;
	}
}