<?php

namespace App\Controllers;

class BaseController
{
    public function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        
        $response = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($response === false) {
            throw new \RuntimeException('Failed to encode JSON response');
        }
        
        fwrite(STDOUT, $response);
        exit;
    }

    protected function getRequestData(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->jsonResponse(['error' => 'Invalid JSON'], 400);
        }
        return $data;
    }

    protected function handleError(\Exception $e): void
    {
        $statusCode = $e->getCode() ?: 500;
        $this->jsonResponse(['error' => $e->getMessage()], $statusCode);
    }
} 