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
                ->mapWithKeys(function ($item, $key) {
                    return [$item->name => array_merge($item->data->toArray(), [
                        'images' => $item->media->map(fn ($media) => [
                            'id' => $media->getKey(),
                            'src' => $media->original_url,
                        ])->toArray(),
                    ])];
                }),
        ]);
    }
}
