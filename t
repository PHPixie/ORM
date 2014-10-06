PHPUnit 4.2.0 by Sebastian Bergmann.

Configuration read from D:\orm\phpunit.xml

.array(2) {
  [0] =>
  class Mock_Model_50f123ba#79 (6) {
    private $__phpunit_invocationMocker =>
    class PHPUnit_Framework_MockObject_InvocationMocker#62 (2) {
      protected $matchers =>
      array(3) {
        ...
      }
      protected $builderMap =>
      array(0) {
        ...
      }
    }
    private $__phpunit_originalObject =>
    NULL
    protected $owner =>
    NULL
    protected $ownerPropertyName =>
    NULL
    protected $relationshipMap =>
    NULL
    protected $properties =>
    array(0) {
    }
  }
  [1] =>
  string(6) "plants"
}
.F.

Time: 164 ms, Memory: 4.75Mb

There was 1 failure:

1) PHPixieTests\ORM\Relationships\Type\Embeds\Type\Many\HandlerTest::testRemoveItems
Expectation failed for method name is equal to <string:shiftCachedModels> when invoked at sequence index 2
Parameter 0 for invocation PHPixie\ORM\Loaders\Loader\Repository\Embedded\ArrayNode::shiftCachedModels(1, 1, Array ()) does not match expected value.
Failed asserting that 1 is identical to 2.

D:\orm\src\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Handler.php:68
D:\orm\src\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Handler.php:37
D:\orm\tests\PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\HandlerTest.php:46

FAILURES!
Tests: 4, Assertions: 5, Failures: 1.
