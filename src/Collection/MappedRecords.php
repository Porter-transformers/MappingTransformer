<?php
namespace ScriptFUSION\Porter\Transform\Mapping\Collection;

use ScriptFUSION\Mapper\Mapping;
use ScriptFUSION\Porter\Collection\RecordCollection;

class MappedRecords extends RecordCollection
{
    /**
     * @var Mapping
     */
    private $mapping;

    public function __construct(\Iterator $records, RecordCollection $previousCollection, Mapping $mapping)
    {
        parent::__construct($records, $previousCollection);

        $this->mapping = $mapping;
    }

    /**
     * @return Mapping
     */
    public function getMapping()
    {
        return $this->mapping;
    }
}
