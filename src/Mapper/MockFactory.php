<?php
namespace ScriptFUSION\Porter\Transform\Mapping\Mapper;

use ScriptFUSION\Porter\Provider\Resource\ProviderResource;
use ScriptFUSION\Porter\Specification\ImportSpecification;
use ScriptFUSION\StaticClass;

final class MockFactory
{
    use StaticClass;

    public static function mockImportSpecification()
    {
        return \Mockery::mock(ImportSpecification::class, [\Mockery::mock(ProviderResource::class)]);
    }
}
