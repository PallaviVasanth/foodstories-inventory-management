<?php

/** Standardizes JSON API responses so every endpoint uses the same response shape. Alternative: encode responses in each controller, but that duplicates logic. */

class Response
{
    /** Sends a success status and JSON data, then stops execution. A helper avoids repeating headers/status/encoding in every controller. */

    public static function success($data = [], $status = 200)
    {
        http_response_code($status);

        echo json_encode([
            "success" => true,
            "data" => $data
        ]);

        exit;
    }

    /** Sends an error status and message as JSON, then stops execution. Centralizing this keeps API errors consistent. */

    public static function error($message, $status = 400)
    {
        http_response_code($status);

        echo json_encode([
            "success" => false,
            "message" => $message
        ]);

        exit;
    }
}