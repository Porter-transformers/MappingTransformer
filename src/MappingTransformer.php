<?php
namespace ScriptFUSION\Porter\Transform\Mapping;

use ScriptFUSION\Mapper\CollectionMapper;
use ScriptFUSION\Mapper\Mapping;
use ScriptFUSION\Porter\Collection\RecordCollection;
use ScriptFUSION\Porter\PorterAware;
use ScriptFUSION\Porter\PorterAwareTrait;
use ScriptFUSION\Porter\Transform\Mapping\Collection\CountableMappedRecords;
use ScriptFUSION\Porter\Transform\Mapping\Collection\MappedRecords;
use ScriptFUSION\Porter\Transform\Mapping\Mapper\PorterMapper;
use ScriptFUSION\Porter\Transform\Transformer;

class MappingTransformer implements Transformer, PorterAware
{
    use PorterAwareTrait;

    /**
     * @var CollectionMapper
     */
    private $mapper;

    /**
     * @var Mapping
     */
    private $mapping;

    public function __construct(Mapping $mapping)
    {
        $this->mapping = $mapping;
    }

    public function __clone()
    {
        $this->mapping = clone $this->mapping;
        // Cloning the mapper doesn't seem to serve any useful purpose.
    }

    public function transform(RecordCollection $records, $context)
    {
        return $this->createMappedRecords(
            $this->getOrCreateMapper()->mapCollection($records, $this->mapping, $context),
            $records,
            $this->mapping
        );
    }

    private function createMappedRecords(\Iterator $records, RecordCollection $previous, Mapping $mapping)
    {
        // Copy count of previous collection because a mapping operation cannot modify the number of records.
        if ($previous instanceof \Countable) {
            return new CountableMappedRecords($records, count($previous), $previous, $mapping);
        }

        return new MappedRecords($records, $previous, $mapping);
    }

    /**
     * @return CollectionMapper
     */
    private function getOrCreateMapper()
    {
        return $this->mapper ?: $this->mapper = new PorterMapper($this->getPorter());
    }

    /**
     * @param CollectionMapper $mapper
     *
     * @return $this
     */
    public function setMapper(CollectionMapper $mapper)
    {
        $this->mapper = $mapper;

        return $this;
    }
}
