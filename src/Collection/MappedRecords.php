<?php
declare(strict_types=1);

namespace ScriptFUSION\Porter\Transform\Mapping\Collection;

use ScriptFUSION\Mapper\Mapping;
use ScriptFUSION\Porter\Collection\RecordCollection;

class MappedRecords extends RecordCollection
{
    private $mapping;

    public function __construct(\Iterator $records, RecordCollection $previousCollection, Mapping $mapping)
    {
        parent::__construct($records, $previousCollection);

        $this->mapping = $mapping;
    }

    public function getMapping(): Mapping
    {
        return $this->mapping;
    }
}
