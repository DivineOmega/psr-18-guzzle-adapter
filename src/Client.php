<?php

namespace DivineOmega\Psr18GuzzleAdapter;


use DivineOmega\Psr18GuzzleAdapter\Exceptions\ClientException;
use DivineOmega\Psr18GuzzleAdapter\Exceptions\NetworkException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client extends GuzzleClient implements ClientInterface
{
    public function __construct(array $config = [])
    {
        $config['http_errors'] = false;

        parent::__construct($config);
    }

    /**
     * Sends a PSR-7 request and returns a PSR-7 response.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->send($request);
        } catch (RequestException $e) {
            throw new NetworkException('Request exception', 0, $e, $request);
        } catch (TransferException $e) {
            throw new NetworkException('Network exception', 0, $e, $request);
        } catch (GuzzleException $e) {
            throw new ClientException('Client exception', 0, $e);
        }
    }
}