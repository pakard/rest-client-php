<?php
namespace Pakard\RestClient;

/**
 *
 */
interface TransportInterface {
    /**
     * @param \Pakard\RestClient\RequestInterface $request
     * @param \Pakard\RestClient\ResponseInterface $response
     * @return \Pakard\RestClient\ResponseInterface
     */
    public function send(RequestInterface $request, ResponseInterface $response);
}
