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
     * Initializes this instance with the specified import instance or import callback.
     *
     * @param Import|\Closure $importOrCallback Import instance or callback returning such an instance.
     *
     * @throws \InvalidArgumentException Import is neither an Import instance nor closure.
     */
    public function __construct(private readonly Import|\Closure $importOrCallback)
    {
    }

    public function __invoke($data, $context = null)
    {
        $specification = clone $this->getOrCreateImport($data, $context);
        $specification->setContext($context);

        $generator = $this->getPorter()->import($specification);

        if ($generator->valid()) {
            return iterator_to_array($generator);
        }
    }

    private function getOrCreateImport($data, $context = null): Import
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
