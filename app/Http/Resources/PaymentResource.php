<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $redeem = $this->resource;

        return [
            'image' => $redeem->image,
            'gateway' => $redeem->gateway,
            'amount' => $redeem->amount,
        ];
    }
}
