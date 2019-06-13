<?php
namespace Pakard\RestClient;

/**
 *
 */
class Request implements RequestInterface {
    /**
     * Verb to use for the HTTP request.
     *
     * @var string
     */
    protected $_method = RequestInterface::METHOD_GET;

    /**
     * URL to send the request to.
     *
     * @var string
     */
    protected $_url;

    /**
     * HTTP entity to send with the request. For POST and PUT requests only.
     *
     * @var mixed
     */
    protected $_body;

    /**
     * @var string
     */
    protected $_bodySent;

    /**
     * @var array
     */
    protected $_headers = [];

    /**
     * @var string
     */
    protected $_headersSent;

    /**
     * Returns HTTP method (verb) set for the request.
     *
     * @return string
     */
    public function getMethod() {
        return $this->_method;
    }

    /**
     * Sets HTTP method for the request.
     *
     * @param string $method
     * @return $this
     */
    public function setMethod($method) {
        $this->_method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->_url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url) {
        $this->_url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody() {
        return $this->_body;
    }

    /**
     * @param mixed $body
     * @return $this
     */
    public function setBody($body) {
        $this->_body = $body;
        return $this;
    }

    /**
     * @return string
     */
    public function encodeBody() {
        switch (strtok($this->getHeader('Content-Type'), ';')) {
            case 'application/json':
                $body = $this->getBody();

                if (isset($body)) {
                    if (is_string($body)) {
                        return $body;
                    }

                    return json_encode($body);
                }

                return NULL;
            case 'multipart/form-data':
                return $this->getBody();
            case 'application/x-www-form-urlencoded':
            default:
                if (is_string($body = $this->getBody())) {
                    return $body;
                }

                return http_build_query($body ?: []);
        }
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addHeader($name, $value) {
        $this->_headers[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getHeader($name) {
        if (isset($this->_headers[$name])) {
            return $this->_headers[$name];
        }

        return NULL;
    }

    /**
     * @return array
     */
    public function getHeaders() {
        return $this->_headers;
    }

    /**
     * @return string
     */
    public function getHeadersSent() {
        return $this->_headersSent;
    }

    /**
     * @param string $header
     * @return $this
     */
    public function setHeadersSent($header) {
        $this->_headersSent = $header;
        return $this;
    }

    /**
     * @return string
     */
    public function getBodySent() {
        return $this->_bodySent;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBodySent($body) {
        $this->_bodySent = $body;
        return $this;
    }

    /**
     * @param string $method
     * @return bool
     * @static
     */
    public static function isBodyAllowed($method) {
        return self::METHOD_POST == strtoupper($method)
        || self::METHOD_PUT == strtoupper($method)
        || self::METHOD_PATCH == strtoupper($method);
    }
}
