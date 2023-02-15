<?php

namespace IMPelevin\PSPShared\LTree\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Umbrellio\LTree\Helpers\LTreeHelper;
use Umbrellio\LTree\Interfaces\LTreeModelInterface;
use Umbrellio\LTree\Interfaces\LTreeServiceInterface;

class LTreeGroupService implements LTreeServiceInterface
{

    private $helper;

    public function __construct()
    {
        $this->helper = new LTreeHelper;
    }

    public function createPath(LTreeModelInterface $model): void
    {
        DB::setDefaultConnection($model->getConnectionName());
        $this->helper->buildPath($model);
    }

    /**
     * @param LTreeModelInterface|Model $model
     */
    public function updatePath(LTreeModelInterface $model): void
    {
        DB::setDefaultConnection($model->getConnectionName());
        $columns = array_intersect_key($model->getAttributes(), array_flip($model->getLtreeProxyUpdateColumns()));

        $this->helper->moveNode($model, $model->ltreeParent, $columns);
        $this->helper->buildPath($model);
    }

    /**
     * @param LTreeModelInterface|Model $model
     */
    public function dropDescendants(LTreeModelInterface $model): void
    {
        DB::setDefaultConnection($model->getConnectionName());
        $columns = array_intersect_key($model->getAttributes(), array_flip($model->getLtreeProxyDeleteColumns()));

        $this->helper->dropDescendants($model, $columns);
    }
}
