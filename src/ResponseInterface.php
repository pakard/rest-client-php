<?php
namespace Pakard\RestClient;

/**
 *
 */
interface ResponseInterface {
    /**
     * The client SHOULD continue with its request. This interim response is
     * used to inform the client that the initial part of the request has been
     * received and has not yet been rejected by the server.
     *
     * @var int
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec8.html#sec8.2.3
     */
    const STATUS_CODE_CONTINUE = 100;

    /**
     * The server understands and is willing to comply with the client's
     * request, via the Upgrade message header field (section 14.42), for a
     * change in the application protocol being used on this connection.
     *
     * @var int
     */
    const STATUS_CODE_SWITCHING_PROTOCOLS = 101;

    /**
     * The request has succeeded. The information returned with the response is
     * dependent on the method used in the request.
     *
     * @var int
     */
    const STATUS_CODE_OK = 200;

    /**
     * The request has been fulfilled and resulted in a new resource being
     * created.
     *
     * @var int
     */
    const STATUS_CODE_CREATED = 201;

    /**
     * The request has been accepted for processing, but the processing has not
     * been completed.
     *
     * @var int
     */
    const STATUS_CODE_ACCEPTED = 202;

    /**
     * The returned metainformation in the entity-header is not the definitive
     * set as available from the origin server, but is gathered from a local or
     * a third-party copy.
     *
     * @var int
     */
    const STATUS_CODE_NON_AUTHORITIVE_INFO = 203;

    /**
     * The server has fulfilled the request but does not need to return an
     * entity-body, and might want to return updated metainformation.
     *
     * @var int
     */
    const STATUS_CODE_NO_CONTENT = 204;

    /**
     * The server has fulfilled the request and the user agent SHOULD reset the
     * document view which caused the request to be sent.
     *
     * @var int
     */
    const STATUS_CODE_RESET_CONTENT = 205;

    /**
     * The server has fulfilled the partial GET request for the resource.
     *
     * @var int
     */
    const STATUS_CODE_PARTIAL_CONTENT = 206;

    /**
     * The requested resource corresponds to any one of a set of
     * representations, each with its own specific location, and agent-driven
     * negotiation information is being provided.
     *
     * @var int
     */
    const STATUS_CODE_MULTIPLE_CHOICES = 300;

    /**
     * The requested resource has been assigned a new permanent URI and any
     * future references to this resource SHOULD use one of the returned URIs.
     *
     * @var int
     */
    const STATUS_CODE_MOVED_PERMANENTLY = 301;

    /**
     * The requested resource resides temporarily under a different URI.
     *
     * @var int
     */
    const STATUS_CODE_FOUND = 302;

    /**
     * The response to the request can be found under a different URI and
     * SHOULD be retrieved using a GET method on that resource.
     *
     * @var int
     */
    const STATUS_CODE_SEE_OTHER = 303;

    /**
     * If the client has performed a conditional GET request and access is
     * allowed, but the document has not been modified, the server SHOULD
     * respond with this status code.
     *
     * @var int
     */
    const STATUS_CODE_NOT_MODIFIED = 304;

    /**
     * The requested resource MUST be accessed through the proxy given by the
     * Location field.
     *
     * @var int
     */
    const STATUS_CODE_USE_PROXY = 305;

    /**
     * The requested resource resides temporarily under a different URI.
     *
     * @var int
     */
    const STATUS_CODE_TEMPORARY_REDIRECT = 307;

    /**
     * The request could not be understood by the server due to malformed
     * syntax.
     *
     * @var int
     */
    const STATUS_CODE_BAD_REQUEST = 400;

    /**
     * The request requires user authentication.
     *
     * @var int
     */
    const STATUS_CODE_UNAUTHORIZED = 401;

    /**
     * This code is reserved for future use.
     *
     * @var int
     */
    const STATUS_CODE_PAYMENT_REQUIRED = 402;

    /**
     * The server understood the request, but is refusing to fulfill it.
     *
     * @var int
     */
    const STATUS_CODE_FORBIDDEN = 403;

    /**
     * The server has not found anything matching the Request-URI.
     *
     * @var int
     */
    const STATUS_CODE_NOT_FOUND = 404;

    /**
     * The method specified in the Request-Line is not allowed for the resource
     * identified by the Request-URI.
     *
     * @var int
     */
    const STATUS_CODE_METHOD_NOT_ALLOWED = 405;

    /**
     * The resource identified by the request is only capable of generating
     * response entities which have content characteristics not acceptable
     * according to the accept headers sent in the request.
     *
     * @var int
     */
    const STATUS_CODE_NOT_ACCEPTABLE = 406;

    /**
     * This code is similar to 401 (Unauthorized), but indicates that the
     * client must first authenticate itself with the proxy.
     *
     * @var int
     */
    const STATUS_CODE_PROXY_AUTH_REQUIRED = 407;

    /**
     * The client did not produce a request within the time that the server was
     * prepared to wait.
     *
     * @var int
     */
    const STATUS_CODE_REQUEST_TIMEOUT = 408;

    /**
     * The request could not be completed due to a conflict with the current
     * state of the resource.
     *
     * @var int
     */
    const STATUS_CODE_CONFLICT = 409;

    /**
     * The requested resource is no longer available at the server and no
     * forwarding address is known.
     *
     * @var int
     */
    const STATUS_CODE_GONE = 410;

    /**
     * The server refuses to accept the request without a defined
     * Content-Length.
     *
     * @var int
     */
    const STATUS_CODE_LENGTH_REQUIRED = 411;

    /**
     * The precondition given in one or more of the request-header fields
     * evaluated to false when it was tested on the server.
     *
     * @var int
     */
    const STATUS_CODE_PRECONDITION_FAILED = 412;

    /**
     * The server is refusing to process a request because the request entity
     * is larger than the server is willing or able to process.
     *
     * @var int
     */
    const STATUS_CODE_REQUEST_ENTITY_TOO_LARGE = 413;

    /**
     * The server is refusing to service the request because the Request-URI is
     * longer than the server is willing to interpret.
     *
     * @var int
     */
    const STATUS_CODE_REQUEST_URI_TOO_LONG = 414;

    /**
     * The server is refusing to service the request because the entity of the
     * request is in a format not supported by the requested resource for the
     * requested method.
     *
     * @var int
     */
    const STATUS_CODE_UNSUPPORTED_MEDIA_TYPE = 415;

    /**
     * A server SHOULD return a response with this status code if a request
     * included a Range request-header field, and none of the range-specifier
     * values in this field overlap the current extent of the selected
     * resource, and the request did not include an If-Range request-header
     * field.
     *
     * @var int
     */
    const STATUS_CODE_REQUESTED_RANGE_NOT_SATISFIABLE = 416;

    /**
     * The expectation given in an Expect request-header field could not be met
     * by this server, or, if the server is a proxy, the server has unambiguous
     * evidence that the request could not be met by the next-hop server.
     *
     * @var int
     */
    const STATUS_CODE_EXPECTATION_FAILED = 417;

    /**
     * This code was defined in 1998 as one of the traditional IETF April
     * Fools' jokes, in RFC 2324, Hyper Text Coffee Pot Control Protocol, and
     * is not expected to be implemented by actual HTTP servers. The RFC
     * specifies this code should be returned by teapots requested to brew
     * coffee. This HTTP status is used as an Easter egg in some websites,
     * including Google.com.
     *
     * @var int
     */
    const STATUS_CODE_IM_A_TEAPOT = 418;

    /**
     * The server encountered an unexpected condition which prevented it from
     * fulfilling the request.
     *
     * @var int
     */
    const STATUS_CODE_INTERNAL_SERVER_ERROR = 500;

    /**
     * The server does not support the functionality required to fulfill the
     * request.
     *
     * @var int
     */
    const STATUS_CODE_NOT_IMPLEMENTED = 501;

    /**
     * The server, while acting as a gateway or proxy, received an invalid
     * response from the upstream server it accessed in attempting to fulfill
     * the request.
     *
     * @var int
     */
    const STATUS_CODE_BAD_GATEWAY = 502;

    /**
     * The server is currently unable to handle the request due to a temporary
     * overloading or maintenance of the server.
     *
     * @var int
     */
    const STATUS_CODE_SERVICE_UNAVAILABLE = 503;

    /**
     * The server, while acting as a gateway or proxy, did not receive a timely
     * response from the upstream server specified by the URI (e.g. HTTP, FTP,
     * LDAP) or some other auxiliary server (e.g. DNS) it needed to access in
     * attempting to complete the request.
     *
     * @var int
     */
    const STATUS_CODE_GATEWAY_TIMEOUT = 504;

    /**
     * The server does not support, or refuses to support, the HTTP protocol
     * version that was used in the request message.
     * 
     * @var int
     */
    const STATUS_CODE_HTTP_VERSION_NOT_SUPPORTED = 505;

    /**
     * @return int
     */
    public function getStatusCode();

    /**
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode);

    /**
     * @return string
     */
    public function getContentType();

    /**
     * @param string $contentType
     * @return $this
     */
    public function setContentType($contentType);

    /**
     * @return string
     */
    public function getRawHeaders();

    /**
     * @param string $headers
     * @return $this
     */
    public function setRawHeaders($headers);

    /**
     * @return string
     */
    public function getRawBody();

    /**
     * @param string $body
     * @return $this
     */
    public function setRawBody($body);

    /**
     * @return mixed
     */
    public function getBody();

    /**
     * @return float
     */
    public function getElapsedTime();

    /**
     * @param float $time
     * @return $this
     */
    public function setElapsedTime($time);
}
