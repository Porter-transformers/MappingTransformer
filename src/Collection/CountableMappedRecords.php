<?php
namespace ScriptFUSION\Porter\Transform\Mapping\Collection;

use ScriptFUSION\Mapper\Mapping;
use ScriptFUSION\Porter\Collection\CountableRecordsTrait;
use ScriptFUSION\Porter\Collection\RecordCollection;

class CountableMappedRecords extends MappedRecords implements \Countable
{
    use CountableRecordsTrait;

    /**
     * @param \Iterator $records
     * @param int $count
     * @param RecordCollection $previousCollection
     * @param Mapping $mapping
     */
    public function __construct(\Iterator $records, $count, RecordCollection $previousCollection, Mapping $mapping)
    {
        parent::__construct($records, $previousCollection, $mapping);

        $this->setCount($count);
    }
}
