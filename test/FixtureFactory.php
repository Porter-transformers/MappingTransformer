<?php
namespace ScriptFUSIONTest;

use Psr\Container\ContainerInterface;
use ScriptFUSION\Porter\Porter;
use ScriptFUSION\StaticClass;

final class FixtureFactory
{
    use StaticClass;

    public static function createPorter(): Porter
    {
        return new Porter(
            \Mockery::mock(ContainerInterface::class)
                ->shouldReceive('has')
                ->andReturn(false)
                ->getMock()
        );
    }
}
