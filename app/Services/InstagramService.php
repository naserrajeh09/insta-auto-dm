<?php
namespace App\Services;

class InstagramService
{
    private string $accessToken;
    private string $apiUrl = "https://graph.facebook.com/v19.0";

    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Send DM to Instagram user by user ID
     *
     * @param string $recipientId Instagram user ID
     * @param string $message
     * @return array
     */
    public function sendMessage(string $recipientId, string $message): array
    {
        $url = "{$this->apiUrl}/{$recipientId}/messages";

        $data = [
            "recipient" => ["id" => $recipientId],
            "message"   => ["text" => $message],
            "access_token" => $this->accessToken
        ];

        $response = $this->makeRequest($url, $data);

        return $response;
    }

    /**
     * Make HTTP POST request to Instagram API
     *
     * @param string $url
     * @param array $data
     * @return array
     */
    private function makeRequest(string $url, array $data): array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $result = curl_exec($ch);

        if ($result === false) {
            return [
                "success" => false,
                "error"   => curl_error($ch)
            ];
        }

        curl_close($ch);

        return [
            "success" => true,
            "response" => json_decode($result, true)
        ];
    }
}
