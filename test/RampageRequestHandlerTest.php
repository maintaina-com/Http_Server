<?php
namespace Horde\Http\Server\Test;
use \PHPUnit\Framework\TestCase;
use Horde\Http\RequestFactory;
use Horde\Http\ResponseFactory;
use Horde\Http\StreamFactory;
use Horde\Http\Server\RampageRequestHandler;
use Psr\Http\Message\ResponseInterface;
use Horde\Http\Server\Middleware\Mock;

class RampageRequestHandlerTest extends TestCase
{
    public function testRunnerSetup()
    {
        $responseFactory = new ResponseFactory;
        $streamFactory = new StreamFactory;
        $handler = new RampageRequestHandler($responseFactory, $streamFactory);
        $requestFactory = new RequestFactory();
        //! $request = $requestFactory->createServerRequest('GET', 'https://www.horde.org');
            //!TypeError: Argument 1 passed to Horde\Http\Server\RampageRequestHandler::addMiddleware()
                //! must be an instance of Psr\Http\Server\MiddlewareInterface, instance of Horde\Http\ServerRequest given.
        //Todo instead with mock, but how to use responseFactory in that context
        $request = new Mock(???);
        $added = $handler->addMiddleware($request);
        $this->assertSame('something like lenght stak', $added);
    }

}

    //Todo public function addMiddleware(MiddlewareInterface $middleware): void
    //Todo public function setPayloadHandler(RequestHandlerInterface $handler): void
    //Todo public function handle(ServerRequestInterface $request): ResponseInterface
