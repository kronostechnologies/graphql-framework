<?php


namespace Kronos\GraphQLFramework\EntryPoint\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;

class HttpVariablesIncorrectlyDefinedException extends FrameworkException
{
	const MSG_GET = 'The "variables" query-string parameter is incorrectly defined. It should contain a valid JSON string.';
	const MSG_POST = 'The "variables" body parameter is incorrectly defined. It should contain a valid JSON string.';
}