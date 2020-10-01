<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'zipcode' => $this->zipcode,
            'phone' => $this->phone,
            'locale' => $this->locale,
            'country_id' => $this->country_id,
            'state_id' => $this->state_id,
            'subscriptions' => $this->subscriptions,
            //'organization_type_id' => $this->organization_type_id,
            'organization_type' => $this->organizationType,
            //'dancers' => $this->dancers,
        ];
    }
}
