<?php
namespace ScriptFUSIONTest\Integration\Porter\Transform\Mapping;

use ScriptFUSION\Mapper\CollectionMapper;
use ScriptFUSION\Mapper\Mapping;
use ScriptFUSION\Porter\Collection\PorterRecords;
use ScriptFUSION\Porter\Porter;
use ScriptFUSION\Porter\Specification\StaticDataImportSpecification;
use ScriptFUSION\Porter\Transform\Mapping\Collection\CountableMappedRecords;
use ScriptFUSION\Porter\Transform\Mapping\Collection\MappedRecords;
use ScriptFUSION\Porter\Transform\Mapping\MappingTransformer;

final class MappingTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Porter
     */
    private $porter;

    protected function setUp()
    {
        $this->porter = new Porter;
    }

    public function testMappingTransformer()
    {
        $records = $this->porter->import(
            (new StaticDataImportSpecification(\Mockery::mock(\Iterator::class)))
                ->addTransformer(
                    (new MappingTransformer($mapping = \Mockery::mock(Mapping::class)))
                        ->setMapper(
                            \Mockery::mock(CollectionMapper::class)
                                ->shouldReceive('mapCollection')
                                ->with(
                                    \Mockery::type(\Iterator::class),
                                    \Mockery::type(Mapping::class),
                                    \Mockery::any())
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
    public function testImportAndMapCountableRecords()
    {
        $records = $this->porter->import(
            (new StaticDataImportSpecification(
                new \ArrayIterator(range(1, $count = 10))
            ))->addTransformer(new MappingTransformer(\Mockery::mock(Mapping::class)))
        );

        self::assertInstanceOf(CountableMappedRecords::class, $records->getPreviousCollection());
        self::assertInstanceOf(\Countable::class, $records);
        self::assertCount($count, $records);
    }
}
