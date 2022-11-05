<?php
declare(strict_types=1);

namespace ScriptFUSION\Porter\Transform\Mapping\Mapper;

use Mockery\MockInterface;
use ScriptFUSION\Porter\Import\Import;
use ScriptFUSION\Porter\Provider\Resource\ProviderResource;
use ScriptFUSION\StaticClass;

final class MockFactory
{
    use StaticClass;

    public static function mockImportSpecification(): Import|MockInterface
    {
        return \Mockery::mock(Import::class, [\Mockery::mock(ProviderResource::class)]);
    }
}
