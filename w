PHPUnit 4.2.0 by Sebastian Bergmann.

Configuration read from C:\orm\phpunit.xml


Fatal error: Call to undefined method Mock_ReusableResult_656e4220::resultStep() in C:\orm\src\PHPixie\ORM\Relationships\Type\ManyToMany\Preloader.php on line 38

Call Stack:
    0.0720     372112   1. {main}() C:\wamp\phpunit.phar:0
    0.0790     573024   2. PHPUnit_TextUI_Command::main() C:\wamp\phpunit.phar:593
    0.0790     576704   3. PHPUnit_TextUI_Command->run() phar://C:/wamp/phpunit.phar/phpunit/TextUI/Command.php:138
    0.2050    2290328   4. PHPUnit_TextUI_TestRunner->doRun() phar://C:/wamp/phpunit.phar/phpunit/TextUI/Command.php:186
    0.2220    2515144   5. PHPUnit_Framework_TestSuite->run() phar://C:/wamp/phpunit.phar/phpunit/TextUI/TestRunner.php:423
    0.2370    2525896   6. PHPUnit_Framework_TestCase->run() phar://C:/wamp/phpunit.phar/phpunit/Framework/TestSuite.php:699
    0.2370    2526456   7. PHPUnit_Framework_TestResult->run() phar://C:/wamp/phpunit.phar/phpunit/Framework/TestCase.php:767
    0.2420    2567192   8. PHPUnit_Framework_TestCase->runBare() phar://C:/wamp/phpunit.phar/phpunit/Framework/TestResult.php:654
    0.4210    3452512   9. PHPUnit_Framework_TestCase->runTest() phar://C:/wamp/phpunit.phar/phpunit/Framework/TestCase.php:831
    0.4210    3453352  10. ReflectionMethod->invokeArgs() phar://C:/wamp/phpunit.phar/phpunit/Framework/TestCase.php:958
    0.4210    3453368  11. PHPixieTests\ORM\Relationships\Relationship\Preloader\Result\MultipleTest->testLoadFor() phar://C:/wamp/phpunit.phar/phpunit/Framework/TestCase.php:958
    0.4640    3604384  12. PHPixie\ORM\Relationships\Relationship\Preloader\Result->valueFor() C:\orm\tests\PHPixieTests\ORM\Relationships\Relationship\Preloader\Result\MultipleTest.php:39
    0.4640    3604416  13. PHPixie\ORM\Relationships\Relationship\Preloader\Result->ensureMapped() C:\orm\src\PHPixie\ORM\Relationships\Relationship\Preloader\Result.php:18
    0.4640    3604456  14. PHPixie\ORM\Relationships\Type\ManyToMany\Preloader->mapItems() C:\orm\src\PHPixie\ORM\Relationships\Relationship\Preloader\Result.php:27

