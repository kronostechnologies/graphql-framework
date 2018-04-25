<?php


namespace Kronos\Tests\GraphQLFramework\Resolver\Controller;


class MockData
{
	const NON_EXISTING_DIR = '/adir/';
	const CONTROLLERS_TEST_DIR = __DIR__ . '/../../../../Mocks/Controllers/';
	const CONTROLLERS_NAMESPACE = '\\Kronos\\Mocks\\Controllers\\';
	const CONTROLLERS_NAMESPACE_SUBDIR = '\\Kronos\\Mocks\\Controllers\\SubDir\\';

	const CONTROLLER_CLASS_A = 'AController';
	const CONTROLLER_CLASS_B = 'BController';
	const CONTROLLER_CLASS_C = 'CController';
	const CONTROLLER_CLASS_D = 'DController';

	const CONTROLLER_NS_A = self::CONTROLLERS_NAMESPACE . self::CONTROLLER_CLASS_A;
	const CONTROLLER_NS_B = self::CONTROLLERS_NAMESPACE . self::CONTROLLER_CLASS_B;
	const CONTROLLER_NS_C = self::CONTROLLERS_NAMESPACE . self::CONTROLLER_CLASS_C;
	const CONTROLLER_NS_D = self::CONTROLLERS_NAMESPACE_SUBDIR . self::CONTROLLER_CLASS_D;

	const SCALAR_CONTROLLER_CLASS = 'ColorController';

	const CONTROLLER_NS_COLOR = self::CONTROLLERS_NAMESPACE . self::SCALAR_CONTROLLER_CLASS;

	const CONTROLLER_FILE_A = self::CONTROLLERS_TEST_DIR . 'AController.php';
	const CONTROLLER_FILE_B = self::CONTROLLERS_TEST_DIR . 'BController.php';
	const CONTROLLER_FILE_C = self::CONTROLLERS_TEST_DIR . 'CController.php';
	const CONTROLLER_FILE_D = self::CONTROLLERS_TEST_DIR . 'SubDir/DController.php';

	const OTHER_CLASS_NS_1 = self::CONTROLLERS_NAMESPACE . 'CustomControllerBase';
	const OTHER_CLASS_NS_2 = self::CONTROLLERS_NAMESPACE . 'SubDir\\OtherClass';

	const OTHER_CLASS_FILE_1 = self::CONTROLLERS_TEST_DIR . 'CustomControllerBase.php';
	const OTHER_CLASS_FILE_2 = self::CONTROLLERS_TEST_DIR . 'SubDir/OtherClass.php';

	const INVALID_TYPE = 'IAmInvalid';

	const CONTROLLER_A_TYPE = 'A';
	const CONTROLLER_B_TYPE = 'B';
	const CONTROLLER_C_TYPE = 'C';
	const CONTROLLER_D_TYPE = 'D';
	const CONTROLLER_D_TYPE_LOWER = 'd';
	const CONTROLLER_D_TYPE_UNTRIMMED = '   d ';
}