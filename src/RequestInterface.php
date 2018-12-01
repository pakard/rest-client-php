<?php
namespace Pakard\RestClient;

/**
 *
 */
interface RequestInterface {
    /**
     * @var string
     */
    const METHOD_GET = 'GET';

    /**
     * @var string
     */
    const METHOD_POST = 'POST';

    /**
     * @var string
     */
    const METHOD_PUT = 'PUT';

    /**
     * @var string
     */
    const METHOD_DELETE = 'DELETE';

    /**
     * @var string
     */
    const METHOD_PATCH = 'PATCH';

    /**
     * Returns HTTP method (verb) set for the request.
     *
     * @return string
     */
    public function getMethod();

    /**
     * Sets HTTP method for the request.
     *
     * @param string $method
     * @return $this
     */
    public function setMethod($method);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url);

    /**
     * @return mixed
     */
    public function getBody();

    /**
     * @param mixed $body
     * @return $this
     */
    public function setBody($body);

    /**
     * @return string
     */
    public function encodeBody();

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addHeader($name, $value);

    /**
     * @param string $name
     * @return string
     */
    public function getHeader($name);

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @return string
     */
    public function getHeadersSent();

    /**
     * @param string $header
     * @return $this
     */
    public function setHeadersSent($header);

    /**
     * @return string
     */
    public function getBodySent();

    /**
     * @param string $body
     * @return $this
     */
    public function setBodySent($body);
}
