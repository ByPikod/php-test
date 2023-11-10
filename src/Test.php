<?php

namespace PHPTest;

use PHPTest\AssertionError;
use ReflectionClass;

const TEXT_SUITE_TEST_RESULT = <<<EOD
Test suite:
%s

Results:
    Suit Name: %s
    Passed: %s
    Failed: %s\n
EOD;

const METHOD_FILTERING_ERRORS = [
    "METHOD_NOT_PUBLIC" => "Method %s is marked as a test but its not public.",
    "METHOD_INCORRECT_PARAMETERS" => "Method %s marked as a test but it has incorrect number of parameters",
    "METHOD_NO_TYPE_HINT" => "Method %s is marked as a test but has no type hint.",
    "METHOD_INCORRECT_TYPE_HINT" => "Method %s is marked as a test but has incorrect type hint %s.",
];

/**
 * TestManager class
 * @since 1.0.0
 */
class Test extends Assertions
{
    /**
     * Private constructor to prevent instantiation.
     * @since 1.0.0
     */
    private function __construct()
    {
    }

    /**
     * Run a test
     * @param string $name Test name
     * @param callable $callback Test callback
     * @return TestResult Test result
     * @since 1.0.0
     */
    private static function test(string $name, callable $callback): TestResult
    {
        $test = new Test();
        $unhandledErrors = [];

        // Capture warnings and notices
        set_error_handler(function ($errno, $errstr, $errfile, $errline) use (&$unhandledErrors) {
            $unhandledErrors[] = new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        // Capture outputs
        ob_start();

        // Run test
        $exception = null;
        try {
            $callback($test);
        } catch (\Throwable $e) {
            $exception = $e;
        }

        // Generate result
        $outputs = ob_get_clean();
        $result = new TestResult($name, $unhandledErrors, $outputs, $exception);

        restore_error_handler();

        return $result;
    }

    /**
     * Run a test
     * @since 1.0.0
     */
    public static function it(string $name, callable $callback): void
    {
        // Just print the result
        echo self::test($name, $callback)->message;
    }

    /**
     * Run all tests
     */
    public static function suite(string $name, callable $callback)
    {
        // Statistics
        $passed = 0;
        $failed = 0;
        $tests = [];

        // Run all tests
        $callback(function ($name, $callback) use (&$passed, &$failed, &$tests) {
            $testResult = self::test($name, $callback); // Run test
            $message = $testResult->message; // Get message
            $message = Utilities::indent(1, $message); // Indent
            $tests[] = $message; // Add to tests
            if ($testResult->passed) $passed++;
            else $failed++;
        });

        $total = $passed + $failed;

        // Format results
        $result = sprintf(
            TEXT_SUITE_TEST_RESULT,
            implode('', $tests),
            $name,
            $passed . "/" . $total,
            $failed . "/" . $total
        );

        // Print results
        echo "\n";
        echo $result;
    }

    /**
     * Filter test methods in a class inherited from Test
     * @param object $obj Object to filter
     * @return array Filtered methods
     * @since 1.0.0
     * @throws MethodFilteringError
     */
    private static function filterTestMethods(object $obj): array
    {
        // Get methods
        $reflection = new ReflectionClass($obj);
        $methods = $reflection->getMethods();

        // Filter methods
        $methods = array_map(function ($method) {
            // Get doc comments of method
            $doc = $method->getDocComment();

            // Match @test annotation and extract test name if there is one
            $matches = [];
            $match = preg_match("/@test(\s(?<testName>.*))?/", $doc, $matches);
            if (!$match)
                return;

            // Check if method is public
            if (!$method->isPublic())
                throw new MethodFilteringError(sprintf(
                    METHOD_FILTERING_ERRORS["METHOD_NOT_PUBLIC"],
                    $method->getName()
                ));

            // Check method has correct number of parameters
            $params = $method->getParameters();
            if (count($params) != 1)
                throw new MethodFilteringError(sprintf(
                    METHOD_FILTERING_ERRORS["METHOD_INCORRECT_PARAMETERS"],
                    $method->getName()
                ));

            // Check parameter type
            $param = $params[0];
            $type = $param->getType();
            if ($type == null)
                throw new MethodFilteringError(sprintf(
                    METHOD_FILTERING_ERRORS["METHOD_NO_TYPE_HINT"],
                    $method->getName()
                ));

            // Check parameter type is Test
            if ($type->getName() != Test::class)
                throw new MethodFilteringError(sprintf(
                    METHOD_FILTERING_ERRORS["METHOD_INCORRECT_TYPE_HINT"],
                    $method->getName(),
                    $type->getName()
                ));

            // Return test name and method
            $testName = $matches['testName'] ?? $method->getName();
            return array(
                'testName' => $testName,
                'method' => $method,
            );
        }, $methods);

        // Remove null values
        $methods = array_filter($methods);

        return $methods;
    }

    /**
     * Run all tests in a class
     * use @test annotation to mark tests
     * @param object $obj Object
     * @since 1.0.0
     */
    public static function suiteClass(object $obj, string $name = null)
    {
        // Filter methods
        $class = get_class($obj);
        $className = $name ?? $class;
        $methods = self::filterTestMethods($obj);

        // Create suite
        self::suite($class, function ($it) use ($methods, $obj) {
            foreach ($methods as $method) {
                $testName = $method['testName'];
                $actualMethod = $method['method'];
                $name = $actualMethod->getName(); // Extract method name to call it.
                $it($testName, function ($test) use ($obj, $name) {
                    if (!method_exists($obj, $name)) // Check if method exists
                        throw new AssertionError("Method {$name} does not exist");
                    $obj->$name($test); // Call method
                });
            }
        });
    }
}
