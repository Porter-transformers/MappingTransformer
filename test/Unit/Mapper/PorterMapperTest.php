<?php
declare(strict_types=1);

namespace ScriptFUSIONTest\Unit\Porter\Transform\Mapping\Mapper;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\AnonymousMapping;
use ScriptFUSION\Mapper\Strategy\Strategy;
use ScriptFUSION\Porter\Collection\RecordCollection;
use ScriptFUSION\Porter\Porter;
use ScriptFUSION\Porter\PorterAware;
use ScriptFUSION\Porter\Transform\Mapping\Mapper\PorterMapper;

/**
 * @see PorterMapper
 */
final class PorterMapperTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testMap(): void
    {
        $mapper = new PorterMapper($porter = \Mockery::mock(Porter::class));

        /** @var RecordCollection $records */
        $records = \Mockery::mock(
            RecordCollection::class,
            [new \ArrayIterator([[1], [2], [3]])]
        )->makePartial();

        $mappedRecords = $mapper->mapCollection(
            $records,
            new AnonymousMapping([$strategy = \Mockery::mock(implode(',', [Strategy::class, PorterAware::class]))])
        );

        $strategy->shouldReceive('__invoke')->andReturnUsing(function ($data) {
            return $data[0] * $data[0];
        })->getMock()->shouldReceive('setPorter')->with($porter)->atLeast()->once();

        self::assertSame([[1], [4], [9]], iterator_to_array($mappedRecords));
    }
}
