<?php
declare(strict_types=1);

namespace ScriptFUSION\Porter\Transform\Mapping\Collection;

use ScriptFUSION\Mapper\Mapping;
use ScriptFUSION\Porter\Collection\CountableRecordsTrait;
use ScriptFUSION\Porter\Collection\RecordCollection;

class CountableMappedRecords extends MappedRecords implements \Countable
{
    use CountableRecordsTrait;

    public function __construct(\Iterator $records, int $count, RecordCollection $previousCollection, Mapping $mapping)
    {
        parent::__construct($records, $previousCollection, $mapping);

        $this->setCount($count);
    }
}
