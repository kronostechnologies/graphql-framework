<?php


namespace Kronos\Tests\GraphQLFramework\Relay;


use Kronos\GraphQLFramework\Relay\Exception\InvalidPayloadException;
use Kronos\GraphQLFramework\Relay\RelayGlobalIdentifier;
use Kronos\GraphQLFramework\Relay\RelayMiddleware;
use PHPUnit\Framework\TestCase;

class RelayMiddlewareTest extends TestCase
{
    const DEFAULT_ID = 'id';
    const SPECIFIC_ID = 'anotherId';

    const RELAY_ID_ROOT = 1;

    /**
     * @var RelayMiddleware
     */
    protected $defaultMiddleware;

    /**
     * @var RelayMiddleware
     */
    protected $specificIdMiddleware;

    public function setUp()
    {
        $this->defaultMiddleware = new RelayMiddleware(self::DEFAULT_ID);
        $this->specificIdMiddleware = new RelayMiddleware(self::SPECIFIC_ID);
    }

    public function test_ArrayRequestNoId_modifyRequest_ReturnsSameRequest()
    {
        $input = ['test' => 'a'];

        $output = $this->defaultMiddleware->modifyRequest($input);

        $this->assertSame($input, $output);
    }

    public function test_ArrayRequestRecursiveNoId_modifyRequest_ReturnsSameRequest()
    {
        $input = ['test' => 'a', 'recur' => ['internal' => 123]];

        $output = $this->defaultMiddleware->modifyRequest($input);

        $this->assertSame($input, $output);
    }

    public function test_ArrayRequestSpecificIdField_modifyRequest_ReturnsRequestWithFlatId()
    {
        $input = [self::DEFAULT_ID => $this->getRelayGID(self::RELAY_ID_ROOT, 'RootDTO')->serialize()];

        $output = $this->defaultMiddleware->modifyRequest($input);

        $expected = [self::DEFAULT_ID => self::RELAY_ID_ROOT];
        $this->assertEquals($expected, $output);
    }

    public function test_ArrayRequestDifferentSpecificIdField_modifyRequest_ReturnsRequestWithFlatId()
    {
        $input = [self::SPECIFIC_ID => $this->getRelayGID(self::RELAY_ID_ROOT, 'RootDTO')->serialize()];

        $output = $this->specificIdMiddleware->modifyRequest($input);

        $expected = [self::SPECIFIC_ID => self::RELAY_ID_ROOT];
        $this->assertEquals($expected, $output);
    }

    public function test_ArrayRequestMalformedRelayGID_modifyRequest_ThrowsInvalidPayloadException()
    {
        $input = [self::SPECIFIC_ID => 'aaa'];

        $this->expectException(InvalidPayloadException::class);

        $this->specificIdMiddleware->modifyRequest($input);
    }

    public function test_NonArrayRequest_modifyRequest_ReturnSameRequest()
    {
        $input = 'abc';

        $output = $this->defaultMiddleware->modifyRequest($input);

        $this->assertSame($input, $output);
    }

    /**
     * @param int $id
     * @param string $entityName
     * @return RelayGlobalIdentifier
     */
    protected function getRelayGID($id, $entityName)
    {
        $relayGID = new RelayGlobalIdentifier();
        $relayGID->setIdentifier($id);
        $relayGID->setEntityName($entityName);

        return $relayGID;
    }
}
