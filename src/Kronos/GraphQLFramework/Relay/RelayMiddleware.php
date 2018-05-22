<?php


namespace Kronos\GraphQLFramework\Relay;


use function array_key_exists;
use function is_object;
use Kronos\GraphQLFramework\FrameworkMiddleware;
use ReflectionClass;

class RelayMiddleware implements FrameworkMiddleware
{
    /**
     * @var string
     */
    protected $idFieldName;

    /**
     * @param string $idFieldName
     */
    public function __construct($idFieldName)
    {
        $this->idFieldName = $idFieldName;
    }

    /**
     * @param array|mixed $request
     * @return array|mixed
     * @throws Exception\InvalidPayloadException
     */
    public function modifyRequest($request)
    {
        if (is_array($request)) {
            if (array_key_exists($this->idFieldName, $request)) {
                $relayIdentifier = new RelayGlobalIdentifier();
                $relayIdentifier->deserialize($request[$this->idFieldName]);

                $request[$this->idFieldName] = $relayIdentifier->getIdentifier();
            }

            foreach ($request as $key => $value) {
                if (is_array($value)) {
                    $request[$key] = $this->modifyRequest($value);
                }
            }

            return $request;
        } else {
            return $request;
        }
    }

    /**
     * @param \stdClass|mixed $response
     * @return \stdClass|mixed
     * @throws \ReflectionException
     */
    public function modifyResponse($response)
    {
        if (is_object($response)) {
            $response = clone $response;
            $reflectionClass = new ReflectionClass($response);

            foreach ($reflectionClass->getProperties() as $property) {
                if ($property->getName() === $this->idFieldName) {
                    $relayGID = new RelayGlobalIdentifier();
                    $relayGID->setIdentifier($property->getValue($response));
                    $relayGID->setEntityName(get_class($response));

                    $property->setValue($response, $relayGID);
                } else if (is_object($property->getValue($response))) {
                    $property->setValue($response, $this->modifyResponse($property->getValue()));
                }
            }

            return $response;
        } else {
            return $response;
        }
    }
}
