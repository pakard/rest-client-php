<?php
use Pakard\RestClient;

/**
 * Tests for {@see Pakard\RestClient\NullTransport} class.
 */
class NullTransportTest extends PHPUnit\Framework\TestCase {
    /**
     * Tests sending with <tt>NullTransport</tt>: it should not throw any
     * exceptions and return with a <tt>NULL</tt> body.
     */
    public function testSend() {
        $this->assertNull(
            (new RestClient\RestClient)
                ->setTransport(new RestClient\NullTransport)
                ->send(
                    RestClient\RequestInterface::METHOD_POST,
                    'schema://example.com'
                )
        );
    }
}
