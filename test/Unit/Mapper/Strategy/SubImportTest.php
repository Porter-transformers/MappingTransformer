<?php
declare(strict_types=1);

namespace ScriptFUSIONTest\Unit\Porter\Transform\Mapping\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ScriptFUSION\Porter\Import\Import;
use ScriptFUSION\Porter\Import\StaticImport;
use ScriptFUSION\Porter\Porter;
use ScriptFUSION\Porter\Transform\Mapping\Mapper\Strategy\InvalidCallbackResultException;
use ScriptFUSION\Porter\Transform\Mapping\Mapper\Strategy\SubImport;
use ScriptFUSIONTest\FixtureFactory;

/**
 * @see SubImport
 */
final class SubImportTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private SubImport|MockInterface $subImport;
    private Porter|MockInterface $porter;

    public function testImport(): void
    {
        $this->createSubImport(new StaticImport(new \ArrayIterator(
            $array = array_map(fn ($int) => [$int], range(1, 5))
        )));

        self::assertSame($array, $this->import());
    }

    public function testImportCallback(): void
    {
        $this->createSubImport(
            function ($data, $context) use (&$array, &$a, &$b) {
                self::assertSame($a, $data);
                self::assertSame($b, $context);

                return new StaticImport(new \ArrayIterator($array));
            }
        );

        self::assertSame($array = [['Charlie']], $this->import($a = 'Alfa', $b = 'Bravo'));
    }

    /**
     * Tests that a sub-import callback that does not return an Import instance raises an exception.
     */
    public function testInvalidImportCallback(): void
    {
        $this->createSubImport(fn () => null);

        $this->expectException(InvalidCallbackResultException::class);

        $this->import();
    }

    private function createSubImport(Import|\Closure $importOrCallback): void
    {
        $this->subImport = new SubImport($importOrCallback);
    }

    private function import($data = null, $context = null)
    {
        $this->subImport->setPorter(FixtureFactory::createPorter());

        return ($this->subImport)($data, $context);
    }
}
