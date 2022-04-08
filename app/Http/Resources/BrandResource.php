<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $url = $this->resource->getFirstMediaUrl('image', 'thumb');
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $url = Str::replaceFirst('/storage', tenant_asset(''), $path);
        }

        return array_merge(Arr::only(parent::toArray($request), ['id', 'name', 'slug']), [
            'image' => $url,
        ]);
    }
}
