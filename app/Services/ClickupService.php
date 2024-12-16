<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ClickUpService
{
    protected $apiToken;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiToken = config('services.clickup.api_token');
        $this->baseUrl = 'https://api.clickup.com/api/v2';
    }

    public function getTasks($listId)
    {
        $response = Http::withHeaders([
            'Authorization' => $this->apiToken,
        ])->get("{$this->baseUrl}/list/{$listId}/task", [
            'include_closed' => true,
            'subtasks' => true,
            'page' => 0,
            'order_by' => 'due_date',
            'reverse' => false,
        ]);

        if ($response->successful()) {
            return $response->json(); 
        }

        throw new \Exception('Failed to fetch tasks: ' . $response->body());
    }
}
