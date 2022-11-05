<?php
declare(strict_types=1);

namespace ScriptFUSIONTest\Integration\Porter\Transform\Mapping;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\CollectionMapper;
use ScriptFUSION\Mapper\Mapping;
use ScriptFUSION\Porter\Collection\PorterRecords;
use ScriptFUSION\Porter\Import\StaticImport;
use ScriptFUSION\Porter\Porter;
use ScriptFUSION\Porter\Transform\Mapping\Collection\CountableMappedRecords;
use ScriptFUSION\Porter\Transform\Mapping\Collection\MappedRecords;
use ScriptFUSION\Porter\Transform\Mapping\MappingTransformer;
use ScriptFUSIONTest\FixtureFactory;

final class MappingTransformerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private Porter $porter;

    protected function setUp(): void
    {
        $this->porter = FixtureFactory::createPorter();
    }

    public function testMappingTransformer(): void
    {
        $records = $this->porter->import(
            (new StaticImport(new \EmptyIterator))
                ->addTransformer(
                    (new MappingTransformer($mapping = \Mockery::mock(Mapping::class)))
                        ->setMapper(
                            \Mockery::mock(CollectionMapper::class)
                                ->shouldReceive('mapCollection')
                                    ->with(
                                        \Mockery::type(\Iterator::class),
                                        \Mockery::type(Mapping::class),
                                        \Mockery::any()
                                    )
                                    ->once()
                                    ->andReturn(new \ArrayIterator([$result = ['foo' => 'bar']]))
                                ->getMock()
                        )
                )
        );

        self::assertInstanceOf(PorterRecords::class, $records);
        self::assertSame($result, $records->current());

        /** @var MappedRecords $previous */
        self::assertInstanceOf(MappedRecords::class, $previous = $records->getPreviousCollection());
        self::assertNotSame($mapping, $previous->getMapping(), 'Mapping was not cloned.');
    }

    /**
     * Tests that when the resource is countable, the count is propagated to the outermost collection via a mapped
     * collection.
     */
    public function testImportAndMapCountableRecords(): void
    {
        $records = $this->porter->import(
            (new StaticImport(
                new \ArrayIterator(array_map(fn ($i) => [$i], range(1, $count = 10)))
            ))->addTransformer(new MappingTransformer(
                \Mockery::spy(Mapping::class)
                    ->shouldReceive('toArray')->andReturn([])
                ->getMock()
            ))
        );

        self::assertInstanceOf(CountableMappedRecords::class, $records->getPreviousCollection());
        self::assertInstanceOf(\Countable::class, $records);
        self::assertCount($count, $records);
    }
}
