<?php

namespace Lib\Testing;

/**
 * Assertions class
 */
class Assertions
{
    /**
     * Throw error
     * @param string $message Error message
     * @since 1.0.0
     */
    public function fatal($message)
    {
        throw new \Exception($message);
    }

    /**
     * Throw assertion error
     * @since 1.0.0
     */
    private function throwAssertionError($message)
    {
        throw new AssertionError($message);
    }

    //
    // Equality assertions
    //

     /**
      * Assert that two values are equal
      * @since 1.0.0
      */
    public function assertEqual($a, $b)
    {
        if ($a != $b)
            $this->throwAssertionError("{$a} != {$b}");
    }

    /**
     * Assert that two values are not equal
     * @since 1.0.0
     */
    public function assertNotEqual($a, $b)
    {
        if ($a == $b)
            $this->throwAssertionError("{$a} === {$b}");
    }

    /**
     * Assert that reference values are equal
     * @since 1.0.0
     */
    public function assetSame(&$a, &$b)
    {
        if ($a !== $b)
            $this->throwAssertionError("{$a} !== {$b}");
    }

    /**
     * Assert that reference values are not equal
     * @since 1.0.0
     */
    public function assertNotSame(&$a, &$b)
    {
        if ($a === $b)
            $this->throwAssertionError("{$a} === {$b}");
    }

    //
    // Nullity assertions
    //

    /**
     * Assert that value is null
     * @since 1.0.0
     */
    public function assertNull($a)
    {
        if ($a !== null)
            $this->throwAssertionError("{$a} !== null");
    }

    /**
     * Assert that value is not null
     * @since 1.0.0
     */
    public function assertNotNull($a)
    {
        if ($a === null)
            $this->throwAssertionError("{$a} === null");
    }

    //
    // Boolean assertions
    //

    /**
     * Assert that value is true
     * @since 1.0.0
     */
    public function assertTrue($a)
    {
        if ($a !== true)
            $this->throwAssertionError("Not true");
    }

    /**
     * Assert that value is false
     * @since 1.0.0
     */
    public function assertFalse($a)
    {
        if ($a !== false)
            $this->throwAssertionError("Not false");
    }

    //
    // Comparision assertions
    //

    /**
     * Assert that value is greater than
     * @since 1.0.0
     */
    public function assertGreaterThan($a, $b)
    {
        if ($a <= $b)
            $this->throwAssertionError("{$a} <= {$b}");
    }

    /**
     * Assert that value is greater than or equal to
     * @since 1.0.0
     */
    public function assertGreaterThanOrEqual($a, $b)
    {
        if ($a < $b)
            $this->throwAssertionError("{$a} < {$b}");
    }

    /**
     * Assert that value is less than
     * @since 1.0.0
     */
    public function assertLessThan($a, $b)
    {
        if ($a >= $b)
            $this->throwAssertionError("{$a} >= {$b}");
    }

    /**
     * Assert that value is less than or equal to
     * @since 1.0.0
     */
    public function assertLessThanOrEqual($a, $b)
    {
        if ($a > $b)
            $this->throwAssertionError("{$a} > {$b}");
    }

    //
    // String assertions
    //

    /**
     * Assert that string contains substring
     * @since 1.0.0
     */
    public function assertContains(string $a, string $b)
    {
        if (strpos($a, $b) === false)
            $this->throwAssertionError("{$a} does not contain {$b}");
    }

    /**
     * Assert that string does not contain substring
     * @since 1.0.0
     */
    public function assertNotContains(string $a, string $b)
    {
        if (strpos($a, $b) !== false)
            $this->throwAssertionError("{$a} contains {$b}");
    }

    /**
     * Assert that string starts with substring
     * @since 1.0.0
     */
    public function assertStartsWith(string $a, string $b)
    {
        if (strpos($a, $b) !== 0)
            $this->throwAssertionError("{$a} does not start with {$b}");
    }

    /**
     * Assert that string does not start with substring
     * @since 1.0.0
     */
    public function assertNotStartsWith(string $a, string $b)
    {
        if (strpos($a, $b) === 0)
            $this->throwAssertionError("{$a} starts with {$b}");
    }

    /**
     * Assert that string ends with substring
     * @since 1.0.0
     */
    public function assertEndsWith(string $a, string $b)
    {
        if (strpos($a, $b) !== strlen($a) - strlen($b))
            $this->throwAssertionError("{$a} does not end with {$b}");
    }

    /**
     * Assert that string does not end with substring
     * @since 1.0.0
     */
    public function assertNotEndsWith(string $a, string $b)
    {
        if (strpos($a, $b) === strlen($a) - strlen($b))
            $this->throwAssertionError("{$a} ends with {$b}");
    }

    //
    // Array assertions
    //

    /**
     * Assert array equals
     * @since 1.0.0
     */
    public function assertArrayEqual(array $a, array $b)
    {
        if ($a != $b)
            $this->throwAssertionError("Arrays are not equal");
    }

    /**
     * Assert array equals
     * @since 1.0.0
     */
    public function assertArrayNotEqual(array $a, array $b)
    {
        if ($a == $b)
            $this->throwAssertionError("Arrays are not equal");
    }

    /**
     * Assert that array contains value
     * @since 1.0.0
     */
    public function assertArrayContains(array $a, $b)
    {
        if (!in_array($b, $a))
            $this->throwAssertionError("Array does not contain {$b}");
    }

    /**
     * Assert that array does not contain value
     * @since 1.0.0
     */
    public function assertArrayNotContains(array $a, $b)
    {
        if (in_array($b, $a))
            $this->throwAssertionError("Array contains {$b}");
    }

    /**
     * Assert that array contains key
     * @since 1.0.0
     */
    public function assertArrayHasKey(array $a, $b)
    {
        if (!array_key_exists($b, $a))
            $this->throwAssertionError("Array does not contain key {$b}");
    }

    /**
     * Assert that array does not contain key
     * @since 1.0.0
     */
    public function assertArrayNotHasKey(array $a, $b)
    {
        if (array_key_exists($b, $a))
            $this->throwAssertionError("Array contains key {$b}");
    }

    /**
     * Assert that array empty
     * @since 1.0.0
     */
    public function assertArrayEmpty(array $a)
    {
        if (!empty($a))
            $this->throwAssertionError("Array is not empty");
    }

    /**
     * Assert that array not empty
     * @since 1.0.0
     */
    public function assertArrayNotEmpty(array $a)
    {
        if (empty($a))
            $this->throwAssertionError("Array is empty");
    }

    // Exception assertions

    /**
     * Assert that exception is thrown
     * @since 1.0.0
     */
    public function assertException(callable $callback)
    {
        try {
            $callback();
        } catch (\Exception $e) {
            return;
        }

        $this->throwAssertionError("Exception not thrown");
    }

    /**
     * Assert that exception is not thrown
     * @since 1.0.0
     */
    public function assertNotException(callable $callback)
    {
        try {
            $callback();
        } catch (\Exception $e) {
            $this->throwAssertionError("Exception thrown");
        }
    }

    //
    // Other assertions
    //

    /**
     * Timeout assertion
     */
    public function assertTimeout(int $seconds, callable $callback)
    {
        $start = microtime(true);
        $callback();
        $end = microtime(true);
        $elapsed = $end - $start;
        if ($elapsed > $seconds)
            $this->throwAssertionError("Timeout after {$elapsed} seconds");
    }

    //
    // Type Assertions
    //

    /**
     * Assert that value is of type
     * @since 1.0.0
     */
    public function assertType($a, string $type)
    {
        if (gettype($a) !== $type)
            $this->throwAssertionError("{$a} is not of type {$type}");
    }

    /**
     * Assert that value is not of type
     * @since 1.0.0
     */
    public function assertNotType($a, string $type)
    {
        if (gettype($a) === $type)
            $this->throwAssertionError("{$a} is of type {$type}");
    }

    /**
     * Assert that value is of class
     * @since 1.0.0
     */
    public function assertInstanceOf(object $a, string $class)
    {
        if (!($a instanceof $class))
            $this->throwAssertionError("{$a} is not an instance of {$class}");
    }

    /**
     * Assert that value is not of class
     * @since 1.0.0
     */
    public function assertNotInstanceOf(object $a, string $class)
    {
        if ($a instanceof $class)
            $this->throwAssertionError("{$a} is an instance of {$class}");
    }
}
