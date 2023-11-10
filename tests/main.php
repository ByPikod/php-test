<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPTest\Test;

// Failing test
echo "*Failing Test*\n";
Test::it("test", function (Test $test) {
    $test->assertEqual(1, 1);
    $test->assertEqual(1, 2);
});
echo "\n";

// Passing test
echo "*Passing Test*\n";
Test::it("test", function (Test $test) {
    $test->assertEqual(1, 1);
});
echo "\n";

// Suite
echo "*Suite Test*\n";
Test::suite("test", function ($it) {
    $it("test", function (Test $test) {
        $test->assertEqual(1, 1);
    });
    $it("test", function (Test $test) {
        $test->assertEqual(1, 2);
    });
});
echo "\n";
