<?php
declare(strict_types=1);

namespace ScriptFUSION\Porter\Transform\Mapping\Collection;

use ScriptFUSION\Mapper\Mapping;
use ScriptFUSION\Porter\Collection\AsyncRecordCollection;
use ScriptFUSION\Porter\Collection\CountableRecordsTrait;

class CountableAsyncMappedRecords extends AsyncMappedRecords implements \Countable
{
    use CountableRecordsTrait;

    public function __construct(
        \Iterator $records,
        int $count,
        AsyncRecordCollection $previousCollection,
        Mapping $mapping
    ) {
        parent::__construct($records, $previousCollection, $mapping);

        $this->setCount($count);
    }
}
