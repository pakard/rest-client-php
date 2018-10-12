<?php
use Pakard\RestClient\TransportException;

/**
 *
 */
class TransportExceptionTest extends PHPUnit\Framework\TestCase {
    /**
     * @coversNothing
     */
    public function testInheritance() {
        $this->assertInstanceOf(
            \Pakard\RestClient\Exception::class,
            new TransportException
        );
    }
}
