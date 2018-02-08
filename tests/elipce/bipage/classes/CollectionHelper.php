<?php namespace Elipce\BiPage\Classes;

use Illuminate\Support\Collection;

/**
 * Class CollectionHelper
 * Synchronize two collections with a key
 * @package Elipce\BiPage\Classes
 */
class CollectionHelper
{
    /**
     * @var array|Collection old collection
     */
    private $oldItems;

    /**
     * @var array|Collection new collection
     */
    private $newItems;

    /**
     * CollectionHelper constructor.
     *
     * @param $old array|Collection
     * @param $new array|Collection
     * @param $key string
     */
    public function __construct($old, $new, $key)
    {
        $this->oldItems = $old->keyBy($key)->toArray();
        $this->newItems = $new->keyBy($key)->toArray();
    }

    /**
     * Get deleted items
     * @return Collection
     */
    public function deleted()
    {
        return Collection::make(
            array_diff_key($this->oldItems, $this->newItems)
        );
    }

    /**
     * Get created items
     * @return Collection
     */
    public function created()
    {
        return Collection::make(
            array_diff_key(
                $this->newItems,
                $this->oldItems
            )
        );
    }

    /**
     * Get updated items
     * @return Collection
     */
    public function updated()
    {
        $items = [];

        // Merge old items with new items
        foreach ($this->oldItems as $key => $item) {
            if (array_key_exists($key, $this->newItems)) {
                $items[] = array_merge($item, $this->newItems[$key]);
            }
        }

        return Collection::make($items);
    }

    /**
     * Get updated and created items
     * @return Collection
     */
    public function all()
    {
        return $this->updated()->merge($this->created());
    }
}