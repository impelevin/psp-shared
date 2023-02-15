<?php

namespace IMPelevin\PSPShared\LTree\Services;

use Umbrellio\LTree\Resources\LTreeResource;

class HierarchicalResources extends LTreeResource
{
    public function toTreeArray($request, $model)
    {
        return array_diff_key(
            $model->getAttributes(),
            array_flip($this->getHidden())
        );
    }
}
