<?php
declare(strict_types=1);

namespace ScriptFUSION\Porter\Transform\Mapping\Collection;

use Amp\Iterator;
use ScriptFUSION\Mapper\Mapping;
use ScriptFUSION\Porter\Collection\AsyncRecordCollection;

class AsyncMappedRecords extends AsyncRecordCollection
{
    private $mapping;

    public function __construct(Iterator $records, AsyncRecordCollection $previousCollection, Mapping $mapping)
    {
        parent::__construct($records, $previousCollection);
    }

    public function getMapping(): Mapping
    {
        return $this->mapping;
    }
}
