<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
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
            'phone_number' => fake()->phoneNumber(),
            'card_status' => 'processing',
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Customer $customer) {
            $customer->signature_filename = $customer->user_id.'-signature.png';
            // Because it's using user_id, this will only work when seeding is done right after a fresh migrtion
            // Could've used guzzlehttp to download random images instead of generating images with faker which has proven to be overly inefficient
            // Currently, this takes ~20,602s to run
            // TODO: change to GuzzleHttp
            // try {
            //     $filename = $customer->user_id.'-signature.png';
                
            //     do {
            //         $fakerImg = fake()->image(storage_path('app\\tmp'), 400, 225);
            //     } while (!file_exists($fakerImg));

            //     // dd($fakerImg);
            //     $fakerImgArr = explode('\\', $fakerImg);
            //     $tmpFilename = $fakerImgArr[count($fakerImgArr) - 1];
            //     if ($tmpFilename === "") dd($fakerImg, Storage::allFiles(), Storage::allDirectories());

            //     Storage::move("tmp\\$tmpFilename", "customers_signatures\\$filename");
            //     $customer->signature_filename = $filename;
            //     Log::info("Successful seeding with id {id}, filename {filename}, tmpFilename {tmpFilename}",
            //     [
            //         'id' => $customer->user_id,
            //         'filename' => $filename,
            //         'tmpFilename' => $tmpFilename,
            //     ]);
            // } catch (\Exception $e) {
            //     Log::error("Error occured with id {id}, filename {filename}, tmpFilename {tmpFilename}, with error: {msg}",
            //     [
            //         'id' => $customer->user_id,
            //         'filename' => $filename,
            //         'msg' => $e->getMessage()
            //     ]);
            //     die();
            // }
        })->afterCreating(function (Customer $customer) {

        });
    }
}
