<?php namespace PlanetaDelEste\ApiShopaholic\Classes\Resource\Offer;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class IndexCollection
 *
 * @package PlanetaDelEste\ApiShopaholic\Classes\Resource\Offer
 */
class IndexCollection extends ResourceCollection
{
    public $collects = ShowResource::class;

    public function toArray($request)
    {
        return $this->collection;
}
}
