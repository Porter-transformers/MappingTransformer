<?php
declare(strict_types=1);

namespace ScriptFUSION\Porter\Transform\Mapping\Mapper;

use ScriptFUSION\Mapper\CollectionMapper;
use ScriptFUSION\Porter\Porter;
use ScriptFUSION\Porter\PorterAware;

class PorterMapper extends CollectionMapper
{
    public function __construct(private readonly Porter $porter)
    {
    }

    protected function injectDependencies($object): void
    {
        parent::injectDependencies($object);

        if ($object instanceof PorterAware) {
            $object->setPorter($this->porter);
        }
    }
}
