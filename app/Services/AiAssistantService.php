<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Team;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class AiAssistantService
{
    /**
     * @param  array<int, array{role: string, content: string}>  $messages
     * @return array{success: bool, message: string}
     */
    public function chat(array $messages, Team $team): array
    {
        $apiKey = $team->getSetting('ai_api_key');
        $model = $team->getSetting('ai_model', 'gpt-4o-mini');
        $provider = $team->getSetting('ai_provider', 'openai');

        if (! $apiKey) {
            return [
                'success' => false,
                'message' => __('AI API key is not configured. Please set it in Settings.'),
            ];
        }

        $systemPrompt = $this->buildSystemPrompt();

        $allMessages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $messages,
        );

        try {
            return match ($provider) {
                'anthropic' => $this->callAnthropic($apiKey, $model, $allMessages),
                default => $this->callOpenAi($apiKey, $model, $allMessages),
            };
        } catch (ConnectionException $e) {
            Log::error('AI Assistant connection error', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => __('Could not connect to AI service. Please try again later.'),
            ];
        }
    }

    /**
     * @param  array<int, array{role: string, content: string}>  $messages
     * @return array{success: bool, message: string}
     */
    private function callOpenAi(string $apiKey, string $model, array $messages): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$apiKey,
        ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
            'model' => $model,
            'messages' => $messages,
            'max_tokens' => 1000,
            'temperature' => 0.7,
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => $response->json('choices.0.message.content', ''),
            ];
        }

        Log::error('OpenAI API error', ['status' => $response->status(), 'body' => $response->body()]);

        return [
            'success' => false,
            'message' => __('AI service error. Please check your API key and try again.'),
        ];
    }

    /**
     * @param  array<int, array{role: string, content: string}>  $messages
     * @return array{success: bool, message: string}
     */
    private function callAnthropic(string $apiKey, string $model, array $messages): array
    {
        $systemMessage = '';
        $chatMessages = [];

        foreach ($messages as $message) {
            if ($message['role'] === 'system') {
                $systemMessage .= $message['content']."\n";
            } else {
                $chatMessages[] = $message;
            }
        }

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'anthropic-version' => '2023-06-01',
        ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
            'model' => $model,
            'max_tokens' => 1000,
            'system' => mb_trim($systemMessage),
            'messages' => $chatMessages,
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => $response->json('content.0.text', ''),
            ];
        }

        Log::error('Anthropic API error', ['status' => $response->status(), 'body' => $response->body()]);

        return [
            'success' => false,
            'message' => __('AI service error. Please check your API key and try again.'),
        ];
    }

    private function buildSystemPrompt(): string
    {
        return 'Te egy raktárkezelő és beszerzés-logisztikai rendszer AI asszisztense vagy. '
            .'A rendszer neve "Storage CMS" és a következő modulokat kezeli: '
            .'Termékek, Kategóriák, Beszállítók, Raktárak, Készletek, Sarzsok, '
            .'Rendelések, Bevételezések, Visszáruk, Leltárak, Intrastat nyilatkozatok. '
            .'Válaszolj magyarul, tömören és szakszerűen. '
            .'Segíts a felhasználónak a rendszer használatában, készletkezelési kérdésekben, '
            .'beszerzési tanácsokkal és logisztikai optimalizálásban.';
    }
}
