<?php
namespace Horde\Http\Server;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * RequestBuilder applies global state or other resources to a ServerRequest
 *
 * Builder uses a fluent interface returning itself.
 * To return the produced request, run ->build()
 */
class RequestBuilder
{
    private ServerRequestInterface $request;
    private ServerRequestFactoryInterface $requestFactory;
    private StreamFactoryInterface $streamFactory;
    private UriFactoryInterface $uriFactory;

    public function __construct(
        ServerRequestFactoryInterface $requestFactory, 
        StreamFactoryInterface $streamFactory,
        UriFactoryInterface $uriFactory
    )
    {
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->uriFactory = $uriFactory;
    }

    /**
     * Create a new server request populated with global state
     */
    public function withGlobalVariables(): self
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($_SERVER['REQUEST_SCHEME']) {
            $scheme = $_SERVER['REQUEST_SCHEME'];
        } elseif (!empty($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS'] == 'off') {
                $scheme = 'http';
            } else {
                $scheme = 'https';
            }
        }
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];
        $queryString = $_SERVER['QUERY_STRING'] ?? '';
        // Always set it, the Uri object will strip it if default
        $port = $_SERVER['SERVER_PORT'] ?? '';
        $path = $_SERVER['REQUEST_URI'] ?? '';
        $path = strtok($path, '?');
        $uriString = sprintf("%s://%s", $scheme, $host);

        $uri = $this->uriFactory->createUri($uriString)
        ->withPort($port)
        ->withPath($path)
        ->withQuery($queryString);
        $body = $this->streamFactory->createStreamFromFile('php://input', 'r+');

        $protocol = !empty($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1';
        $headers = getallheaders();

        $this->request = $this->requestFactory
            ->createServerRequest($method, $uri, $_SERVER)
            ->withBody($body)
            ->withCookieParams($_COOKIE)
            ->withQueryParams($_GET)
            ->withParsedBody($_POST)
            ->withUploadedFiles($_FILES)
            ->withProtocolVersion($protocol);
        $this->withHeaders($headers);
        return $this;
    }

    public function withHeaders(array $headers): self
    {
        foreach ($headers as $header => $value) {
            $this->request = $this->request->withHeader($header, $value);
        }
        return $this;
    }

    public function build(): ServerRequestInterface
    {
        return $this->request;
    }
}
