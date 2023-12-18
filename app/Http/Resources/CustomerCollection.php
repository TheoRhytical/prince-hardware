<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCollection extends ResourceCollection
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'customers';

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $customers = parent::toArray($request);
        foreach($customers as &$customer) {
            $customer['card_status'] = match ($customer['card_status']) {
                'processing' => 'On Process',
                'released'=> 'Released',
            };
            $customer['signature_filename'] = route('customer.signature', ['filename' => $customer['signature_filename']]);
        }
        return $customers;
    }
}
