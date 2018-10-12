<?php
namespace Pakard\RestClient;

/**
 *
 */
class RestClient {
    /**
     * @var string
     */
    protected $_baseUrl;

    /**
     * @var \Pakard\RestClient\TransportInterface
     */
    protected $_transport;

    /**
     * @var \Pakard\RestClient\RequestInterface
     */
    protected $_request;

    /**
     * @var \Pakard\RestClient\ResponseInterface
     */
    protected $_response;

    /**
     * @var array
     */
    protected $_before = [];

    /**
     * @var array
     */
    protected $_after = [];

    /**
     * @return string
     */
    public function getBaseUrl() {
        return $this->_baseUrl;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setBaseUrl($url) {
        $this->_baseUrl = $url;
        return $this;
    }

    /**
     * @param string $url
     * @param mixed $params
     * @return string
     */
    public function prepareUrl($url, $params = NULL) {
        // Extending input with base URL.
        if (!strlen($url)) {
            $url = $this->getBaseUrl();
        } elseif (!preg_match('#https{0,1}://#', $url)) {
            $url = rtrim($this->getBaseUrl(), '/') . '/' . ltrim($url, '/');
        }

        // Appending query string params.
        if (!empty($params)) {
            if (!is_scalar($params)) {
                $params = http_build_query($params);
            }
            $url .= (strpos($url, '?') === FALSE ? '?' : '&') . $params;
        }

        return $url;
    }

    /**
     * @return \Pakard\RestClient\TransportInterface
     */
    public function getTransport() {
        return $this->_transport;
    }

    /**
     * @param \Pakard\RestClient\TransportInterface $transport
     * @return $this
     */
    public function setTransport(TransportInterface $transport) {
        $this->_transport = $transport;
        return $this;
    }

    /**
     * @return \Pakard\RestClient\RequestInterface
     */
    public function createRequest() {
        return new Request;
    }

    /**
     * @return \Pakard\RestClient\RequestInterface
     */
    public function getRequest() {
        return $this->_request;
    }

    /**
     * @param \Pakard\RestClient\RequestInterface $request
     * @return $this
     */
    public function setRequest(RequestInterface $request) {
        $this->_request = $request;
        return $this;
    }

    /**
     * @return \Pakard\RestClient\ResponseInterface
     */
    public function createResponse() {
        return new Response;
    }

    /**
     * @return \Pakard\RestClient\ResponseInterface
     */
    public function getResponse() {
        return $this->_response;
    }

    /**
     * @param \Pakard\RestClient\ResponseInterface $response
     * @return $this
     */
    public function setResponse(ResponseInterface $response) {
        $this->_response = $response;
        return $this;
    }

    /**
     * @param callable $callback
     * @param bool $prepend
     * @return $this
     */
    public function before(callable $callback, $prepend = FALSE) {
        if ($prepend) {
            array_unshift($this->_before, $callback);
        } else {
            $this->_before[] = $callback;
        }

        return $this;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function after(callable $callback) {
        $this->_after[] = $callback;
        return $this;
    }

    /**
     * @return $this
     */
    public function callBeforeCallbacks() {
        array_walk(
            $this->_before,
            function (callable $callback) {
                $callback($this);
            }
        );
        return $this;
    }

    /**
     * @return $this
     */
    public function callAfterCallbacks() {
        array_walk(
            $this->_after,
            function (callable $callback) {
                $callback($this);
            }
        );
        return $this;
    }

    /**
     *
     */
    public function beforeSend() {

    }

    /**
     *
     */
    public function afterSend() {

    }

    /**
     * @param string $method
     * @param string $url
     * @param mixed $data
     * @return mixed
     * @throws \LogicException
     */
    public function send($method, $url, $data = NULL) {
        if (!$this->getTransport()) {
            throw new \LogicException('Transport is not set');
        }

        $this
            ->setRequest(
                $this->createRequest()
                    ->setMethod(strtoupper($method))
                    ->setUrl(
                        $this->prepareUrl(
                            $url,
                            !Request::isBodyAllowed($method) ? $data : NULL
                        )
                    )
                    ->setBody(Request::isBodyAllowed($method) ? $data : NULL)
            )
            ->setResponse($this->createResponse())
            ->beforeSend();

        $this->callBeforeCallbacks();

        try {
            $this->getTransport()->send($this->getRequest(), $this->getResponse());
        } catch (TransportException $e) {
            $this->getResponse()->setRawBody($e->getMessage());
            throw $e;
        } finally {
            $this->callAfterCallbacks();
            $this->afterSend();
        }

        $status_code = $this->getResponse()->getStatusCode();

        if (!Response::isStatusAccepted($status_code)) {
            throw new StatusCodeException('', $status_code);
        }

        return $this->getResponse()->getBody();
    }
}
