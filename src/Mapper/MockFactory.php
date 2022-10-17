<?php
declare(strict_types=1);

namespace ScriptFUSION\Porter\Transform\Mapping\Mapper;

use Mockery\MockInterface;
use ScriptFUSION\Porter\Provider\Resource\ProviderResource;
use ScriptFUSION\Porter\Specification\ImportSpecification;
use ScriptFUSION\StaticClass;

final class MockFactory
{
    use StaticClass;

    /**
     * @return ImportSpecification|MockInterface
     */
    public static function mockImportSpecification()
    {
        return \Mockery::mock(ImportSpecification::class, [\Mockery::mock(ProviderResource::class)]);
    }
}
