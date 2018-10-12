<?php
use Pakard\RestClient\Response;
use Pakard\RestClient\ResponseInterface;

/**
 *
 */
class ResponseTest extends PHPUnit\Framework\TestCase {
    /**
     *
     */
    public function testInheritance() {
        $this->assertInstanceOf('Pakard\RestClient\ResponseInterface', new Response);
    }

    /**
     *
     */
    public function testStatusCode() {
        // Testing default value;
        $this->assertNull((new Response)->getStatusCode());

        // Testing method chainability.
        $this->assertSame($request = new Response, $request->setStatusCode(200));

        // Testing method transitivity.
        $this->assertSame(404, (new Response)->setStatusCode(404)->getStatusCode());
    }

    /**
     *
     */
    public function testContentType() {
        // Testing default value;
        $this->assertNull((new Response)->getContentType());

        // Testing method chainability.
        $this->assertSame($request = new Response, $request->setContentType('text/html'));

        // Testing method transitivity.
        $this->assertSame(
            'application/json; charset=UTF-8',
            (new Response)
                ->setContentType('application/json; charset=UTF-8')
                ->getContentType()
        );
    }

    /**
     *
     */
    public function testRawHeaders() {
        // Testing default value;
        $this->assertNull((new Response)->getRawHeaders());

        // Testing method chainability.
        $this->assertSame($request = new Response, $request->setRawHeaders('Accept: */*'));

        // Testing method transitivity.
        $this->assertSame(
            'testvalue',
            (new Response)
                ->setRawHeaders('testvalue')
                ->getRawHeaders()
        );
    }

    /**
     *
     */
    public function testRawBody() {
        // Testing default value;
        $this->assertNull((new Response)->getRawBody());

        // Testing method chainability.
        $this->assertSame($request = new Response, $request->setRawBody('content'));

        // Testing method transitivity.
        $this->assertSame(
            'testvalue',
            (new Response)
                ->setRawBody('testvalue')
                ->getRawBody()
        );
    }

    /**
     *
     */
    public function testBody() {
        // Testing default.
        $this->assertNull((new Response)->getBody());

        // Testing pass-thru.
        $this->assertSame('["content"]', (new Response)->setRawBody('["content"]')->getBody());

        // Testing explicit default type.
        $this->assertSame(
            '["content"]',
            (new Response)
                ->setRawBody('["content"]')
                ->setContentType('application/x-www-form-urlencoded')
                ->getBody()
        );

        // Testing JSON parse.
        $this->assertEquals(
            $result = (object) ['one' => 1, 'two' => 2, 'three' => []],
            (new Response)
                ->setRawBody('{"one":1,"two":2,"three":[]}')
                ->setContentType('application/json; charset=UTF-8')
                ->getBody()
        );

        // Testing XML parse.
        $this->assertInstanceOf(
            'SimpleXMLElement',
            (new Response)
                ->setRawBody('<xml />')
                ->setContentType('application/xml')
                ->getBody()
        );

        // Testing XML parse; alt. MIME-type.
        $this->assertInstanceOf(
            'SimpleXMLElement',
            (new Response)
                ->setRawBody('<xml />')
                ->setContentType('text/xml')
                ->getBody()
        );
    }

    /**
     * @expectedException Pakard\RestClient\ResponseParserException
     */
    public function testBody_invalidJson() {
        (new Response)
            ->setRawBody('invalid JSON')
            ->setContentType('application/json')
            ->getBody();
    }

    /**
     * @expectedException Pakard\RestClient\ResponseParserException
     */
    public function testBody_invalidXml() {
        (new Response)
            ->setRawBody('<invalid xml>')
            ->setContentType('text/xml')
            ->getBody();
    }

    /**
     * @return array
     */
    public function statusCodeDataProvider() {
        return [
            [NULL, FALSE],
            [0, FALSE],
            [ResponseInterface::STATUS_CODE_CONTINUE, TRUE],
            [ResponseInterface::STATUS_CODE_SWITCHING_PROTOCOLS, TRUE],
            [ResponseInterface::STATUS_CODE_OK, TRUE],
            [ResponseInterface::STATUS_CODE_CREATED, TRUE],
            [ResponseInterface::STATUS_CODE_ACCEPTED, TRUE],
            [ResponseInterface::STATUS_CODE_NON_AUTHORITIVE_INFO, TRUE],
            [ResponseInterface::STATUS_CODE_NO_CONTENT, TRUE],
            [ResponseInterface::STATUS_CODE_RESET_CONTENT, TRUE],
            [ResponseInterface::STATUS_CODE_PARTIAL_CONTENT, TRUE],
            [ResponseInterface::STATUS_CODE_MULTIPLE_CHOICES, TRUE],
            [ResponseInterface::STATUS_CODE_MOVED_PERMANENTLY, TRUE],
            [ResponseInterface::STATUS_CODE_FOUND, TRUE],
            [ResponseInterface::STATUS_CODE_SEE_OTHER, TRUE],
            [ResponseInterface::STATUS_CODE_NOT_MODIFIED, TRUE],
            [ResponseInterface::STATUS_CODE_USE_PROXY, TRUE],
            [ResponseInterface::STATUS_CODE_TEMPORARY_REDIRECT, TRUE],
            [ResponseInterface::STATUS_CODE_BAD_REQUEST, FALSE],
            [ResponseInterface::STATUS_CODE_UNAUTHORIZED, FALSE],
            [ResponseInterface::STATUS_CODE_PAYMENT_REQUIRED, FALSE],
            [ResponseInterface::STATUS_CODE_FORBIDDEN, FALSE],
            [ResponseInterface::STATUS_CODE_NOT_FOUND, FALSE],
            [ResponseInterface::STATUS_CODE_METHOD_NOT_ALLOWED, FALSE],
            [ResponseInterface::STATUS_CODE_NOT_ACCEPTABLE, FALSE],
            [ResponseInterface::STATUS_CODE_PROXY_AUTH_REQUIRED, FALSE],
            [ResponseInterface::STATUS_CODE_REQUEST_TIMEOUT, FALSE],
            [ResponseInterface::STATUS_CODE_CONFLICT, FALSE],
            [ResponseInterface::STATUS_CODE_GONE, FALSE],
            [ResponseInterface::STATUS_CODE_LENGTH_REQUIRED, FALSE],
            [ResponseInterface::STATUS_CODE_PRECONDITION_FAILED, FALSE],
            [ResponseInterface::STATUS_CODE_REQUEST_ENTITY_TOO_LARGE, FALSE],
            [ResponseInterface::STATUS_CODE_REQUEST_URI_TOO_LONG, FALSE],
            [ResponseInterface::STATUS_CODE_UNSUPPORTED_MEDIA_TYPE, FALSE],
            [ResponseInterface::STATUS_CODE_REQUESTED_RANGE_NOT_SATISFIABLE, FALSE],
            [ResponseInterface::STATUS_CODE_EXPECTATION_FAILED, FALSE],
            [ResponseInterface::STATUS_CODE_IM_A_TEAPOT, FALSE],
            [ResponseInterface::STATUS_CODE_INTERNAL_SERVER_ERROR, FALSE],
            [ResponseInterface::STATUS_CODE_NOT_IMPLEMENTED, FALSE],
            [ResponseInterface::STATUS_CODE_BAD_GATEWAY, FALSE],
            [ResponseInterface::STATUS_CODE_SERVICE_UNAVAILABLE, FALSE],
            [ResponseInterface::STATUS_CODE_GATEWAY_TIMEOUT, FALSE],
            [ResponseInterface::STATUS_CODE_HTTP_VERSION_NOT_SUPPORTED, FALSE],
        ];
    }

    /**
     * @param int $status_code
     * @param bool $expected
     * @dataProvider statusCodeDataProvider
     */
    public function testIsStatusAccepted($status_code, $expected) {
        $this->assertSame($expected, Response::isStatusAccepted($status_code));
    }
}
