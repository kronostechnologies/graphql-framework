<?php


namespace Kronos\Tests\GraphQLFramework\Relay;


use Kronos\GraphQLFramework\Relay\Exception\InvalidPayloadException;
use Kronos\GraphQLFramework\Relay\RelayGlobalIdentifier;
use Kronos\GraphQLFramework\Relay\RelayMiddleware;
use Kronos\Mocks\DTO\NoIdDTO;
use Kronos\Mocks\DTO\SpecificIdDTO;
use Kronos\Mocks\DTO\WithIdDTO;
use PHPUnit\Framework\TestCase;

class RelayMiddlewareTest extends TestCase
{
    const DEFAULT_ID = 'id';
    const SPECIFIC_ID = 'anotherId';
    const ALREADY_ENCODED_ID = 'eyJlbnRpdHkiOiJUZXN0RFRPIiwiaWQiOjExMX0=';

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
        $this->defaultMiddleware = new RelayMiddleware(self::DEFAULT_ID, '');
        $this->specificIdMiddleware = new RelayMiddleware(self::SPECIFIC_ID, '');
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

        $this->assertEquals(self::RELAY_ID_ROOT, $output[self::DEFAULT_ID]);
    }

    public function test_ArrayRequestSpecificIdField_modifyRequest_ReturnsRequestWithEntityName()
    {
        $input = [self::DEFAULT_ID => $this->getRelayGID(self::RELAY_ID_ROOT, 'RootDTO')->serialize()];

        $output = $this->defaultMiddleware->modifyRequest($input);

        $this->assertEquals('RootDTO', $output[self::DEFAULT_ID . '.entity']);
    }

    public function test_ArrayRequestDifferentSpecificIdField_modifyRequest_ReturnsRequestWithFlatId()
    {
        $input = [self::SPECIFIC_ID => $this->getRelayGID(self::RELAY_ID_ROOT, 'RootDTO')->serialize()];

        $output = $this->specificIdMiddleware->modifyRequest($input);

        $this->assertEquals(self::RELAY_ID_ROOT, $output[self::SPECIFIC_ID]);
    }

    public function test_ArrayRequestDifferentSpecificIdField_modifyRequest_ReturnsRequestWithEntityName()
    {
        $input = [self::SPECIFIC_ID => $this->getRelayGID(self::RELAY_ID_ROOT, 'RootDTO')->serialize()];

        $output = $this->specificIdMiddleware->modifyRequest($input);

        $this->assertEquals('RootDTO', $output[self::SPECIFIC_ID . '.entity']);
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

    public function test_ObjectResponseNoId_modifyResponse_DoesNotModifyInputObject()
    {
        $input = new NoIdDTO();
        $input->val = 111;

        $output = $this->defaultMiddleware->modifyResponse($input);

        $this->assertNotSame($input, $output);
    }

    public function test_ObjectResponseNoId_modifyResponse_ReturnsEqualResponse()
    {
        $input = new NoIdDTO();
        $input->val = 111;

        $output = $this->defaultMiddleware->modifyResponse($input);

        $this->assertEquals($input, $output);
    }

    public function test_ObjectResponseSpecificIdField_modifyResponse_ReturnsEncapsulatedIdField()
    {
        $input = new WithIdDTO();
        $input->id = self::DEFAULT_ID;

        $output = $this->defaultMiddleware->modifyResponse($input);

        $this->assertNotSame($input->id, $output->id);
    }

    public function test_ObjectResponseDifferentSpecificIdField_modifyResponse_ReturnsEncapsulatedIdField()
    {
        $input = new SpecificIdDTO();
        $input->anotherId = self::DEFAULT_ID;

        $output = $this->specificIdMiddleware->modifyResponse($input);

        $this->assertNotSame($input->anotherId, $output->anotherId);
    }

    public function test_ObjectResponseIdAlreadyEncoded_modifyResponse_DoesNotModifyIdField()
    {
        $input = new SpecificIdDTO();
        $input->anotherId = self::ALREADY_ENCODED_ID;

        $output = $this->specificIdMiddleware->modifyResponse($input);

        $this->assertSame(self::ALREADY_ENCODED_ID, $output->anotherId);
    }

    public function test_NonObjectResponse_modifyResponse_ReturnsSameResponse()
    {
        $input = 'abc';

        $output = $this->defaultMiddleware->modifyResponse($input);

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
