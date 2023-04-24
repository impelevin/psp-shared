<?php

namespace IMPelevin\PSPShared\LTree\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Umbrellio\LTree\Collections\LTreeCollection;
use Umbrellio\LTree\Interfaces\LTreeModelInterface;
use Umbrellio\LTree\Traits\LTreeModelTrait;
use IMPelevin\PSPShared\LTree\Traits\LTreeResource;

/**
 * Class extending LTree Package
 */
class LTreeModel extends Model implements LTreeModelInterface
{
    use LTreeResource;
}
