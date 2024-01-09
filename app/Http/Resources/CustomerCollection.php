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
            // $dob = $customer['date_of_birth'];
            $customer['card_status'] = match ($customer['card_status']) {
                'processing' => 'On Process',
                'released'=> 'Released',
            };
            $customer['signature_filename'] = route('admin.customer.signature', ['filename' => $customer['signature_filename']]);
            $customer['date_of_birth_formatted'] = date('F n, Y', strtotime($customer['date_of_birth']));
            // $customer['date_of_birth'] = date('d/m/Y', strtotime($customer['date_of_birth']));
            $customer['registered_date'] = date('F n, Y', strtotime($customer['created_at']));
            $customer['released_date'] = ($customer['released_at']) ? date('F n, Y', strtotime($customer['released_at'])) : 'Processing';
        }
        return $customers;
    }
}
