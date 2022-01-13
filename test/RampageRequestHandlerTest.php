<?php
namespace Horde\Http\Server\Test;
use \PHPUnit\Framework\TestCase;
use Horde\Http\RequestFactory;
use Horde\Http\ResponseFactory;
use Horde\Http\StreamFactory;
use Horde\Http\Server\RampageRequestHandler;
use Psr\Http\Message\ResponseInterface;
use Horde\Http\Server\Middleware\Mock;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;

class RampageRequestHandlerTest extends TestCase
{
    public function testAddMiddleware()
    {

        $responseFactory = new ResponseFactory;
        $streamFactory = new StreamFactory;
        $handler = new RampageRequestHandler($responseFactory, $streamFactory);
        $requestFactory = new RequestFactory();
        //! $request = $requestFactory->createServerRequest('GET', 'https://www.horde.org');
            //!TypeError: Argument 1 passed to Horde\Http\Server\RampageRequestHandler::addMiddleware()
                //! must be an instance of Psr\Http\Server\MiddlewareInterface, instance of Horde\Http\ServerRequest given.
        //Todo instead with mock, but how to use responseFactory in that context
        //$request = new Mock(???);
        $middlewareMock = $this->createMock(MiddlewareInterface::class);
        $handler->addMiddleware($middlewareMock);
        $next = $handler->nextMiddleware();
        $this->assertInstanceOf(MiddlewareInterface::class, $next);
    }

    public function testSetPayloadHandler()
    {

        $responseFactory = new ResponseFactory;
        $streamFactory = new StreamFactory;
        $handler = new RampageRequestHandler($responseFactory, $streamFactory);
        $requestHandlerMock = $this->createMock(RequestHandlerInterface::class);
        $handler->setPayloadHandler($requestHandlerMock);
        //! how to test the payload?
        $this->assertSame("???","???");
    }

    public function testHandle()
    {

        $responseFactory = new ResponseFactory;
        $streamFactory = new StreamFactory;
        $handler = new RampageRequestHandler($responseFactory, $streamFactory);
        $serverRequestMock = $this->createMock(ServerRequestInterface::class);
        $handled = $handler->handle($serverRequestMock);
        //! may be not the best way to test this
        $this->assertSame(null,$handled->body);
    }
}

    //Todo public function addMiddleware(MiddlewareInterface $middleware): void
    //Todo public function setPayloadHandler(RequestHandlerInterface $handler): void
    //Todo public function handle(ServerRequestInterface $request): ResponseInterface
