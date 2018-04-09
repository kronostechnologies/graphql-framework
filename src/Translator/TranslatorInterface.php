<?php

/**
 * Translates a GraphQL entity to an external data type or vice-versa.
 *
 * Doc comment blocks should be use to infer the external and GraphQL types.
 */
interface TranslatorInterface
{
	public function translate($externalData);
}