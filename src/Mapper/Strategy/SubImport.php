<?php
declare(strict_types=1);

namespace ScriptFUSION\Porter\Transform\Mapping\Mapper\Strategy;

use ScriptFUSION\Mapper\Strategy\Strategy;
use ScriptFUSION\Porter\Import\Import;
use ScriptFUSION\Porter\PorterAware;
use ScriptFUSION\Porter\PorterAwareTrait;

class SubImport implements Strategy, PorterAware
{
    use PorterAwareTrait;

    /**
     * Initializes this instance with the specified import specification or specification callback.
     *
     * @param Import|\Closure $importOrCallback Import specification or callback returning such a specification.
     *
     * @throws \InvalidArgumentException Specification is not an ImportSpecification or callable.
     */
    public function __construct(private readonly Import|\Closure $importOrCallback)
    {
    }

    public function __invoke($data, $context = null)
    {
        $specification = clone $this->getOrCreateImportSpecification($data, $context);
        $specification->setContext($context);

        $generator = $this->getPorter()->import($specification);

        if ($generator->valid()) {
            return iterator_to_array($generator);
        }
    }

    private function getOrCreateImportSpecification($data, $context = null): Import
    {
        if ($this->importOrCallback instanceof Import) {
            return $this->importOrCallback;
        }

        if (($import = ($this->importOrCallback)($data, $context)) instanceof Import) {
            return $import;
        }

        throw new InvalidCallbackResultException('Callback failed to create an instance of Import.');
    }
}
