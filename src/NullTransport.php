<?php
namespace Pakard\RestClient;

/**
 * Transport that does nothing, really. There may be edge cases when it comes
 * handy.
 */
class NullTransport implements TransportInterface {
    /**
     * @param \Pakard\RestClient\RequestInterface $request
     * @param \Pakard\RestClient\ResponseInterface $response
     * @return \Pakard\RestClient\ResponseInterface
     */
    public function send(RequestInterface $request, ResponseInterface $response) {
        return $response->setStatusCode(ResponseInterface::STATUS_CODE_OK);
    }
}
