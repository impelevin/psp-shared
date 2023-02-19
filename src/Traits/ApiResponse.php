<?php

namespace IMPelevin\PSPShared\Traits;

use Error;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

trait ApiResponse
{
    /**
     * @param $result
     * @param $message
     * @param $statusCode
     * @return JsonResponse
     */
    protected function respondSuccess($result, $message = '', $statusCode = 200): JsonResponse
    {
        return $this->apiResponse([
            'success' => true,
            'message' => $message,
            'result' => $result
        ],
            $statusCode
        );
    }

    /**
     * @param ValidationException $exception
     * @return JsonResponse
     */
    protected function respondValidationErrors(ValidationException $exception): JsonResponse
    {
        return $this->apiResponse(
            [
                'success' => false,
                'message' => $exception->getMessage(),
                'errors' => $exception->errors()
            ],
            422
        );
    }

    /**
     * Return generic json response with the given data.
     *
     * @param $data
     * @param $statusCode
     * @param $headers
     * @return JsonResponse
     */
    protected function apiResponse($data = [], $statusCode = 200, $headers = []): JsonResponse
    {
        $result = $this->parseGivenData($data, $statusCode, $headers);
        return response()->json(
            $result['content'], $result['statusCode'], $result['headers']
        );
    }


    /**
     * @param $data
     * @param $statusCode
     * @param $headers
     * @return array
     */
    public function parseGivenData($data = [], $statusCode = 200, $headers = []): array
    {
        $responseStructure = [
            'success' => $data['success'] ?? false,
            'message' => $data['message'] ?? null,
            'result' => $data['result'] ?? null,
        ];
        if (isset($data['errors'])) {
            $responseStructure['errors'] = $data['errors'];
        }


        if (isset($data['exception']) && ($data['exception'] instanceof Error || $data['exception'] instanceof Exception)) {
            if (config('app.env') !== 'production') {
                $responseStructure['exception'] = [
                    'message' => $data['exception']->getMessage(),
                    'file' => $data['exception']->getFile(),
                    'line' => $data['exception']->getLine(),
                    'code' => $data['exception']->getCode(),
                    'trace' => $data['exception']->getTrace(),
                ];
            }

            if ($statusCode === 200) {
                $statusCode = 500;
            }
        }
        if ($data['success'] === false) {
            if (isset($data['error_code'])) {
                $responseStructure['error_code'] = $data['error_code'];
            } else {
                $responseStructure['error_code'] = 1;
            }
        }

        return ["content" => $responseStructure, "statusCode" => $statusCode, "headers" => $headers];
    }
}
