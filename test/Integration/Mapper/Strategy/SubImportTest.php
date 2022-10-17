<?php
declare(strict_types=1);

namespace ScriptFUSIONTest\Integration\Porter\Transform\Mapping\Mapper\Strategy;

use PHPUnit\Framework\TestCase;
use ScriptFUSION\Porter\Specification\StaticDataImportSpecification;
use ScriptFUSION\Porter\Transform\Mapping\Mapper\Strategy\SubImport;
use ScriptFUSIONTest\FixtureFactory;

/**
 * @see SubImport
 */
final class SubImportTest extends TestCase
{
    public function testSpecificationCallbackCanCreateSpecification(): void
    {
        $record = 'foo';

        $import = new SubImport(function () use ($record) {
            return new StaticDataImportSpecification(new \ArrayIterator([[$record]]));
        });
        $import->setPorter(FixtureFactory::createPorter());

        $records = $import(null);

        self::assertSame([$record], $records[0]);
    }
}
