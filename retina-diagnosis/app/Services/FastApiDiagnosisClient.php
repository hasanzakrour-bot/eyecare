<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class FastApiDiagnosisClient
{
    public function predict(string $absoluteImagePath): array
    {
        $baseUrl = rtrim(config('services.fastapi.url', 'http://127.0.0.1:8001'), '/');

        if (! file_exists($absoluteImagePath)) {
            throw new RuntimeException('صورة التشخيص غير موجودة على السيرفر.');
        }

        $response = Http::timeout(120)
            ->attach('file', file_get_contents($absoluteImagePath), basename($absoluteImagePath))
            ->post($baseUrl . '/predict');

        if (! $response->successful()) {
            throw new RuntimeException('FastAPI لم يرجع استجابة صحيحة. HTTP Status: ' . $response->status());
        }

        $payload = $response->json();

        if (! is_array($payload)) {
            throw new RuntimeException('استجابة FastAPI غير مفهومة.');
        }

        return $payload;
    }

    public function health(): bool
    {
        try {
            $baseUrl = rtrim(config('services.fastapi.url', 'http://127.0.0.1:8001'), '/');
            return Http::timeout(5)->get($baseUrl . '/health')->successful();
        } catch (\Throwable) {
            return false;
        }
    }
}
