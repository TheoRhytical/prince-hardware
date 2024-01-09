<?php

namespace Database\Factories;

use App\Models\Customer;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'date_of_birth' => fake()->date(),
            'address' => fake()->address(),
            'phone_number' => fake()->numerify('+63-9##-###-####'),
            'card_status' => 'processing',
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Customer $customer) {
        })->afterCreating(function (Customer $customer) {
            try {
                $filename = $customer->id.'-signature.jpg';
                $client = new Client();

                $client->request('GET', 'https://picsum.photos/400/225.jpg', [
                    'sink' => Storage::path('customer-signatures/' . $filename)
                ]);

                // $response = $client->get('https://picsum.photos/400/225.jpg', ['stream' => true]);
                // $stream = \GuzzleHttp\Psr7\StreamWrapper::getResource($response->getBody());
                // Storage::disk('s3')->put($filename, $stream);

                $customer->signature_filename = $filename;
                $customer->save();
                Log::info("Successful seeding with id {id}, filename {filename}, tmpFilename {tmpFilename}",
                [
                    'user_id' => $customer->user_id,
                    'customer_id' => $customer->id,
                    'filename' => $filename,
                ]);
            } catch (\Exception $e) {
                Log::error("Error occured with id {id}, filename {filename}, tmpFilename {tmpFilename}, with error: {msg}",
                [
                    'user_id' => $customer->user_id,
                    'customer_id' => $customer->id,
                    'filename' => $filename,
                    'msg' => $e->getMessage()
                ]);
                die();
            }

        });
    }
}
