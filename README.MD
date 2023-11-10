# PHP Test

![License](https://img.shields.io/github/license/ByPikod/php-test.svg?style=for-the-badge)
![Maintaned](https://img.shields.io/badge/Maintained%3F-yes-green.svg?style=for-the-badge)
![Commits](https://img.shields.io/github/commits-since/ByPikod/php-test/latest.svg?style=for-the-badge)
![Forks](https://img.shields.io/github/forks/ByPikod/php-test.svg?style=for-the-badge)
![Stars](https://img.shields.io/github/stars/ByPikod/php-test.svg?style=for-the-badge)
![Watchers](https://img.shields.io/github/watchers/ByPikod/php-test.svg?style=for-the-badge)

Simple PHP test library for unit testing.

> [!Warning]
Minimum PHP version for this package is PHP7.

## Installation

Add this package to your project with composer:

```bash
composer require bypikod/php-test
```

## Getting Started

Basic Test:

```php
Test::it("test", function (Test $test) {
    $test->assertEqual(1, 2);
});
```

Suite Test:

```php
Test::suite("test", function ($it) {
    $it("test", function (Test $test) {
        $test->assertEqual(1, 1);
    });
    $it("test", function (Test $test) {
        $test->assertEqual(1, 2);
    });
});
```

Suite Test with Class:

```php
class RouterTest extends Router
{
    /**
     * Test get branch
     * @test Branch Getter Test
     * @since 1.0.0
     */
    public function getBranchTest(Test $test): void
    {
        // Test get branch
        $this->getBranch('/a/b', false)[] = 'test';
        $test->assertArrayContains($this->getBranch('/a/b', false), 'test');
        // Test by popping the last element
        $this->getBranch('/a/b/test', true)[] = 'test';
        $test->assertArrayContains($this->getBranch('/a/b', false), 'test');
    }
}


$router = new RouterTest();
Test::suiteClass($router);
```

## Contribute

Contributers are welcome! Just make sure you understand the follow the rules below:

- Contributions must follow the [PSR-2](https://www.php-fig.org/psr/psr-2/) coding standard.
- Make sure you're writing documentation that covers your code changes.
- Follow the [Code of Conduct](CODE_OF_CONDUCT.md).

And you should know your submissions will be under [MIT License](LICENSE).

## License

This project is licensed under the terms of the MIT License.

You are free to use this project in compliance with the MIT License. If you decide to use, modify, or redistribute this software, you must include a copy of the original license and copyright notice in all copies or substantial portions of the software.

For more information about the MIT License, visit: [MIT License](https://www.mit.edu/~amini/LICENSE.md).
