<?php
namespace Pakard\RestClient;

/**
 *
 */
class Response implements ResponseInterface {
    /**
     * @var int
     */
    protected $_statusCode;

    /**
     * @var string
     */
    protected $_contentType;

    /**
     * @var array
     */
    protected $_headers;

    /**
     * @var string
     */
    protected $_rawHeaders;

    /**
     * @var string
     */
    protected $_rawBody;

    /**
     * @var float
     */
    protected $_elapsedTime;

    /**
     * @return int
     */
    public function getStatusCode() {
        return $this->_statusCode;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode) {
        $this->_statusCode = $statusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getContentType() {
        return $this->_contentType;
    }

    /**
     * @param string $contentType
     * @return $this
     */
    public function setContentType($contentType) {
        $this->_contentType = $contentType;
        return $this;
    }

    /**
     * @return string
     */
    public function getRawHeaders() {
        return $this->_rawHeaders;
    }

    /**
     * @param string $headers
     * @return $this
     */
    public function setRawHeaders($headers) {
        $this->_rawHeaders = $headers;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRawBody() {
        return $this->_rawBody;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setRawBody($body) {
        $this->_rawBody = $body;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody() {
        return $this->parseBody($this->getRawBody(), $this->getContentType());
    }

    /**
     * @inheritdoc
     */
    public function getElapsedTime() {
        return $this->_elapsedTime;
    }

    /**
     * @inheritdoc
     */
    public function setElapsedTime($time) {
        $this->_elapsedTime = $time;
        return $this;
    }

    /**
     * @param string $body
     * @param string $contentType
     * @return mixed
     */
    protected function parseBody($body, $contentType) {
        switch (strtok($contentType, ';')) {
            case 'application/json':
                return $this->parseJsonBody($body);
            case 'application/xml':
            case 'text/xml':
                return $this->parseXmlBody($body);
            default:
                return $this->getRawBody();
        }
    }

    /**
     * Processes and returns JSON result.
     *
     * On JSON parse error it sets an {@see \Antavo\RestParserException} with
     * error code & message then returns the original result string.
     *
     * @param string $result
     * @return mixed
     * @throws \Pakard\RestClient\ResponseParserException
     */
    protected function parseJsonBody($result) {
        $parsed_result = json_decode($result);

        if (($error = json_last_error()) !== JSON_ERROR_NONE) {
            throw new ResponseParserException(json_last_error_msg(), $error);
        }

        return $parsed_result;
    }

    /**
     * Parses XML body.
     *
     * @param string $body
     * @return mixed  Returns a {@see \SimpleXMLElement} object on successful
     * operation, or the original result string otherwise.
     * @throws \Pakard\RestClient\ResponseParserException
     * @static
     */
    protected function parseXmlBody($body) {
        if (($parsed_body = @simplexml_load_string($body)) === FALSE) {
            throw new ResponseParserException(error_get_last()['message']);
        }

        return $parsed_body;
    }

    /**
     * @param int $status_code
     * @return bool
     * @static
     */
    public static function isStatusAccepted($status_code) {
        return $status_code >= 100 && $status_code < 400;
    }

    /**
     * @param int $status_code
     * @return string
     * @static
     */
    public static function getStatusText($status_code) {
        static $messages = [
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Time-out',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Large',
            415 => 'Unsupported Media Type',
            416 => 'Requested range not satisfiable',
            417 => 'Expectation Failed',
            418 => 'I\'m a Teapot',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Time-out',
            505 => 'HTTP Version not supported',
        ];

        if (isset($messages[$status_code])) {
            return $messages[$status_code];
        }

        return NULL;
    }
}
