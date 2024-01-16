<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class HttpJsonResponse
{
	/**
	 * Return a Json Response for errors
	 * @param int|\Symfony\Component\HttpFoundation\Response $code Http Status Code
	 * @param string $message Message to send to client
	 */
	public static function error(int $code, string $message = 'Internal Server Error'): JsonResponse
	{
		return response()->json([
			'message' => $message,
		], $code);
	}
}