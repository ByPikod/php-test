<?php

namespace PHPTest;

use PHPTest\AssertionError;

const TEST_PASSED = "[\u{2713}] Test passed: %s\n";

const TEST_PASSED_EXTRA = <<<EOD
[\u{2713}] Test passed with details:
    Name: %s%s
EOD;

const TEST_THROWN_EXCEPTION = <<<EOD
[\u{2717}] Test thrown an exception:
    Name: %s
    Message: %s
    At: %s
    Traceback:
    %s%s
EOD;

const TEST_FAILED_ASSERTION = <<<EOD
[\u{2717}] Test failed:
    Name: %s
    Message: %s
    Assertion: %s%s
EOD;

/**
 * TestManager class
 * @since 1.0.0
 */
class TestResult
{
    public string $name;
    public bool $passed;
    public array $unhandledErrors;
    public string $outputs;
    public \Throwable $exception;
    public string $message;

    /**
     * TestResult constructor
     * @param string $name Test name
     * @param array $unhandledErrors Unhandled errors if there is.
     * @param string $outputs Console outputs if there is.
     * @param \Throwable $exception Exception if there is.
     * @since 1.0.0
     */
    public function __construct(
        string $name,
        array $unhandledErrors,
        string $outputs,
        \Throwable $exception = null
    ) {
        $this->name = $name;
        $this->unhandledErrors = $unhandledErrors;
        $this->outputs = $outputs;
        if ($exception !== null) {
            $this->passed = false;
            $this->exception = $exception;
        } else {
            $this->passed = true;
        }
        $this->message = $this->generateResults();
    }

    /**
     * Convert trace to string
     * @param array $trace Trace
     * @return string Trace as string
     */
    public static function traceToString(array $trace): string
    {
        // Extract data from trace
        $file = $trace['file'];
        $line = $trace['line'];
        $function = $trace['function'];

        // Convert arguments to string
        $args = array_map(function ($arg) {
            if (is_string($arg))
                return "'$arg'";
            if (is_array($arg))
                return 'Array';
            if (is_object($arg))
                return get_class($arg);
            return $arg;
        }, $trace['args'] ?? []);

        // Join arguments with comma
        $args = implode(', ', $args);

        // Return formatted string
        return "$file:$line | $function($args)";
    }

    /**
     * Get traceback as string
     * @param array $traceback Traceback
     * @return string Traceback as string
     * @since 1.0.0
     */
    public static function getTracebackAsString(array $traceback): string
    {
        $traceback = array_map(function ($item) {
            return self::traceToString($item);
        }, $traceback);

        $traceback[] = '{main}';
        $traceback = implode("\n" . Utilities::indent(), $traceback);
        return $traceback;
    }

    /**
     * Remove test functions from traceback
     * @param array $traceback Traceback
     * @return array Traceback without test functions
     * @since 1.0.0
     */
    private static function cleanTraceback(array $traceback): array
    {
        if (sizeof($traceback) === 0) return $traceback;

        //  Remove test functions from traceback
        $traceback = array_filter($traceback, function ($trace) {
            // Get trace file path
            $path = dirname($trace['file']);
            // If path is the same as the current directory, return false
            if ($path == __DIR__) return false;
            // Otherwise, keep the trace
            return true;
        });

        return $traceback;
    }

    /**
     * Convert unhandled errors list to string
     * @param array $unhandledErrors Unhandled errors
     * @return string Unhandled errors as string
     * @since 1.0.0
     */
    private static function unhandledErrorsToString(array $unhandledErrors): string
    {
        $text = "Warnings:\n";

        // If there is unhandled errors (warnings, notices, etc.)
        $unhandledErrors = array_map(function ($error) {
            // Convert errors to string
            $message = sprintf(
                "%s:%s | %s",
                $error->getFile(),
                $error->getLine(),
                $error->getMessage()
            );
            $message = Utilities::bullet($message);
            return Utilities::indent() . $message; // Add indentation
        }, $unhandledErrors);

        // Join errors with new line
        $text .= implode("\n", $unhandledErrors);
        return $text;
    }

    /**
     * Convert outputs to string
     * @param string $outputs Outputs
     * @return string Outputs as string
     * @since 1.0.0
     */
    private static function formatOutputs(string $outputs): string
    {
        $text = "HTML Output:\n";
        $outputs = trim($outputs);
        $text .= Utilities::indent(1, $outputs);
        return $text;
    }

    /**
     * Generate addition data (unhandled errors and outputs)
     * @return string Addition data as string
     * @since 1.0.0
     */
    private function generateAdditionData(): string
    {
        $addition = "\n";
        // If there is no unhandled errors and no outputs
        if (sizeof($this->unhandledErrors) < 1 && $this->outputs == '') {
            return $addition;
        }

        // If there is unhandled errors
        if (sizeof($this->unhandledErrors) > 0) {
            $addition .= self::unhandledErrorsToString($this->unhandledErrors) . "\n";
        }

        // If there is outputs
        if ($this->outputs != '') {
            $addition .= self::formatOutputs($this->outputs) . "\n";
        }

        return $addition;
    }

    /**
     * Convert TestResult to string
     * @param TestResult $result TestResult
     * @return string TestResult as string
     * @since 1.0.0
     */
    private function generateResults(): string
    {
        // If passed or no exception, return passed message
        if ($this->passed) {
            // If there is no unhandled errors and no outputs
            if (sizeof($this->unhandledErrors) < 1 && $this->outputs == '') {
                return sprintf(TEST_PASSED, $this->name);
            }
            // Addition data (unhandled errors and outputs)
            $addition = $this->generateAdditionData();
            $addition = Utilities::indent(1, $addition);
            // Format and return message
            return sprintf(
                TEST_PASSED_EXTRA,
                $this->name,
                $addition
            );
        }

        // Otherwise, return failed message
        if ($this->exception instanceof AssertionError) {
            // If exceptiion is AssertionError, return assertion message
            $exception = $this->exception;
            $traceback = $exception->getTrace();
            $assertion = self::traceToString($traceback[1]);
            // Addition data (unhandled errors and outputs)
            $addition = $this->generateAdditionData();
            $addition = Utilities::indent(1, $addition);
            // Format and return message
            return sprintf(
                TEST_FAILED_ASSERTION,
                $this->name,
                $exception->getMessage(),
                $assertion,
                $addition
            );
        }

        // Otherwise, return exception message
        $exception = $this->exception;
        // Get traceback as string
        $traceback = $exception->getTrace();
        $traceback = self::cleanTraceback($traceback);
        $traceback = self::getTracebackAsString($traceback);
        $traceback = Utilities::indent(1, $traceback);
        // Get exception location
        $at = "{$exception->getFile()}:{$exception->getLine()}";
        // Addition data (unhandled errors and outputs)
        $addition = $this->generateAdditionData();
        $addition = Utilities::indent(1, $addition);

        // Format and return message
        return sprintf(
            TEST_THROWN_EXCEPTION,
            $this->name,
            $exception->getMessage(),
            $at,
            $traceback,
            $addition
        );
    }

    public function __toString(): string
    {
        return $this->message;
    }
}
