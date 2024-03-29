<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'attributes' => $this->resource->attributes ?? [],
            'variations' => $this->when($request->routeIs('*.products.edit'), function () {
                return $this->resource->variations
                    ->mapWithKeys(function ($item, $key) {
                        return [$item->name => $item->toArray()];
                    });
            }),
        ]);
    }
}
