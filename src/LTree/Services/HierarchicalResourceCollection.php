<?php

namespace IMPelevin\PSPShared\LTree\Services;

use Umbrellio\LTree\Resources\LTreeResourceCollection;

class HierarchicalResourceCollection extends LTreeResourceCollection
{

    public $collects = HierarchicalResources::class;

    public function __construct($resource, $sort = null, bool $usingSort = true, bool $loadMissing = true)
    {
        parent::__construct($resource, $sort, $usingSort, $loadMissing);
    }

}
