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
                'released' => 'Released',
            };
            $customer['signature_filename'] = route('admin.customer.signature', ['filename' => $customer['signature_filename']]);
            $customer['date_of_birth_formatted'] = date('F d, Y', strtotime($customer['date_of_birth']));
            $customer['registered_date'] = date('F d, Y', strtotime($customer['registered_at']));
            $customer['registered_date_ymd'] = date('Y-m-d', strtotime($customer['registered_at']));
            $customer['released_date'] = ($customer['released_at']) ? date('F d, Y', strtotime($customer['released_at'])) : 'Processing';
            $customer['released_date_ymd'] = ($customer['released_at']) ? date('Y-m-d', strtotime($customer['released_at'])) : null;
        }
        return $customers;
    }
}
