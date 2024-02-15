<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class Helper
{
    public const SUCCESS_MESSAGE = 'Success';

    /**
     * @param int         $statusCode
     * @param int         $code
     * @param object|null $message
     *
     * @return JsonResponse
     */
    public static function JsonResponse($code = 0, $message = null, $statusCode = 200)
    {
        $response = new JsonResponse();
        $response->setEncodingOptions(\JSON_UNESCAPED_UNICODE);
        $response->setCharset('utf8');
        $response->setStatusCode($statusCode);

        $type = $statusCode > 299 ? 'Error' : 'Success';
        $response->setData([
            'code' => $code,
            'message' => $type,
            'data' => $message,
        ]);

        return $response;
    }
}
