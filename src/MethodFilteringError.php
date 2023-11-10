<?php

namespace PHPTest;

use ReflectionMethod;

/**
 * This exception is thrown when there is an error in the method filtering of Test::suiteClass method.
 * @see Test::suiteClass
 * @since 1.0.0
 */
class MethodFilteringError extends \Exception
{
}
