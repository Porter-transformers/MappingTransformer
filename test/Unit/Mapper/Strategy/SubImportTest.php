<?php
declare(strict_types=1);

namespace ScriptFUSIONTest\Unit\Porter\Transform\Mapping\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ScriptFUSION\Porter\Collection\PorterRecords;
use ScriptFUSION\Porter\Collection\ProviderRecords;
use ScriptFUSION\Porter\Porter;
use ScriptFUSION\Porter\Provider\Resource\ProviderResource;
use ScriptFUSION\Porter\Transform\Mapping\Mapper\MockFactory;
use ScriptFUSION\Porter\Transform\Mapping\Mapper\Strategy\InvalidCallbackResultException;
use ScriptFUSION\Porter\Transform\Mapping\Mapper\Strategy\SubImport;

/**
 * @see SubImport
 */
final class SubImportTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var MockInterface|SubImport */
    private $subImport;

    /** @var MockInterface|Porter */
    private $porter;

    protected function setUp(): void
    {
        $this->createSubImport();
    }

    public function testInvalidCreate(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->createSubImport(true);
    }

    public function testImport(): void
    {
        self::assertNull($this->import());

        $this->mockPorter(
            new \ArrayIterator(
                $array = array_map(
                    function ($int) {
                        return [$int];
                    },
                    range(1, 5)
                )
            )
        );

        self::assertSame($array, $this->import());
    }

    public function testSpecificationCallback(): void
    {
        $this->createSubImport(
            function ($data, $context) {
                self::assertSame('foo', $data);
                self::assertSame('bar', $context);

                return MockFactory::mockImportSpecification();
            }
        );

        $this->import('foo', 'bar');
    }

    /**
     * Tests that a sub-import callback that does not return an ImportSpecification raises an exception.
     */
    public function testInvalidSpecificationCallback(): void
    {
        $this->createSubImport(static function () {
            // Intentionally empty.
        });

        $this->expectException(InvalidCallbackResultException::class);

        $this->import();
    }

    private function createSubImport($specification = null): void
    {
        $specification = $specification ?: MockFactory::mockImportSpecification();

        $this->subImport = new SubImport($specification);
    }

    private function mockPorter(\Iterator $return = null): Porter
    {
        return $this->porter = \Mockery::mock(Porter::class)
            ->shouldReceive('import')
                ->andReturn(
                    new PorterRecords(
                        new ProviderRecords(
                            $return ?? new \EmptyIterator,
                            \Mockery::mock(ProviderResource::class)
                        ),
                        MockFactory::mockImportSpecification()
                    )
                )
                ->byDefault()
            ->getMock()
        ;
    }

    private function import($data = null, $context = null)
    {
        $this->subImport->setPorter($this->porter ?: $this->mockPorter());

        return ($this->subImport)($data, $context);
    }
}
