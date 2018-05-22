<?php


namespace Kronos\GraphQLFramework\Relay;


use Kronos\GraphQLFramework\Relay\Exception\InvalidPayloadException;

class RelayGlobalIdentifier
{
    /**
     * @var int
     */
    protected $identifier;

    /**
     * @var
     */
    protected $entityName;

    /**
     * @param string $entityName
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @param int $id
     */
    public function setIdentifier($id)
    {
        $this->identifier = $id;
    }

    /**
     * @return int
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        $gidArray = [
            'entity' => $this->getEntityName(),
            'id' => $this->getIdentifier()
        ];

        $gidJSONEncoded = json_encode($gidArray);
        $gidB64Encoded = base64_encode($gidJSONEncoded);

        return $gidB64Encoded;
    }

    /**
     * @param string $gidB64
     * @throws InvalidPayloadException
     */
    public function deserialize($gidB64)
    {
        $gidB64Decoded = base64_decode($gidB64, true);
        $this->validateDecodedB64($gidB64Decoded);

        $gidDecodedJSON = json_decode($gidB64Decoded);
        $this->validateDecodedJSON($gidDecodedJSON);

        $this->setEntityName($gidDecodedJSON->entity);
        $this->setIdentifier($gidDecodedJSON->id);
    }

    /**
     * @param bool|string $b64
     * @throws InvalidPayloadException
     */
    protected function validateDecodedB64($b64)
    {
        if ($b64 === false) {
            throw new InvalidPayloadException(InvalidPayloadException::INVALID_B64_CODE);
        }
    }

    /**
     * @param bool|\stdClass $decodedJson
     * @throws InvalidPayloadException
     */
    protected function validateDecodedJSON($decodedJson)
    {
        if (!isset($decodedJson->id) || !isset($decodedJson->entity)) {
            throw new InvalidPayloadException(InvalidPayloadException::INVALID_JSON_CODE);
        }

        if (!is_numeric($decodedJson->id)) {
            throw new InvalidPayloadException(InvalidPayloadException::INVALID_INDEX_TYPE_CODE);
        }
    }
}
