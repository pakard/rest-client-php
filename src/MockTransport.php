<?php
namespace Pakard\RestClient;

/**
 *
 */
class MockTransport implements TransportInterface {
    /**
     * @var callable
     */
    protected $_callback;

    /**
     * @param \Pakard\RestClient\RequestInterface $request
     * @param \Pakard\RestClient\ResponseInterface $response
     * @return \Pakard\RestClient\ResponseInterface
     */
    public function send(RequestInterface $request, ResponseInterface $response) {
        if (!is_null($callback = $this->_callback)) {
            $callback($request, $response);
        }

        return $response;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function setCallback(callable $callback) {
        $this->_callback = $callback;
        return $this;
    }
}
