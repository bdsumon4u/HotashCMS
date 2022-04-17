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
            'media' => [],
            'attributes' => $this->resource->attributes ?? [],
            'variations' => $this->resource->variations
                ->mapWithKeys(fn ($item, $key) => [
                    $item->name => $item->data
                ]),
        ]);
    }
}
