<?php

namespace IMPelevin\PSPShared\LTree\Traits;

use IMPelevin\PSPShared\LTree\Services\HierarchicalResourceCollection;
use IMPelevin\PSPShared\LTree\Services\LTreeGroupService;
use Umbrellio\LTree\Traits\LTreeModelTrait;

trait LTreeResource
{
    use LTreeModelTrait;

    /**
     * LTree Service for working
     * with hierarchical model path
     *
     * @var LTreeGroupService
     */
    private LTreeGroupService $lTreeService;

    public function __construct(array $attributes = [])
    {
        $this->lTreeService = new LTreeGroupService;
        parent::__construct($attributes);
    }

    /**
     * Writes the appropriate path
     * to the current model
     */
    public function lTreeCreatePath(): void
    {
        $this->lTreeService->createPath($this);
    }

    /**
     * Updates the path to the current model
     * and its children
     */
    public function lTreeUpdatePath(): void
    {
        $this->lTreeService->updatePath($this);
    }

    /**
     * Deletes the children of the current model
     */
    public function lTreeDropDescendants(): void
    {
        $this->lTreeService->dropDescendants($this);
    }

    /**
     * @param mixed $itemClass
     */
    public function setItemClass($itemClass): void
    {
        $this->itemClass = $itemClass;
    }

    /**
     * @param mixed $intermediateClass
     */
    public function setIntermediateClass($intermediateClass): void
    {
        $this->intermediateClass = $intermediateClass;
    }

    /**
     * Returns a collection of parent
     * and children in hierarchical form
     *
     * @param LTreeCollection|Collection $resource
     * @param array|null $sort
     * @param bool $usingSort
     * @param bool $loadMissing
     * @return HierarchicalResourceCollection
     */
    public static function resourceCollection(LTreeCollection|Collection $resource,
                                              array|null $sort = null,
                                              bool $usingSort = true,
                                              bool $loadMissing = false): HierarchicalResourceCollection
    {
        return new HierarchicalResourceCollection(
            $resource,
            $sort,
            $usingSort,
            $loadMissing,
        );
    }
}