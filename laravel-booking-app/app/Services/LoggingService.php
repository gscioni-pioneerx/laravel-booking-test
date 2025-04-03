<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LoggingService
{
    /**
     * Create a new class instance.
     */
    public function logOperation(string $operation, string $model, int $id, array $data = []): void
    {
        Log::channel('operations')->info("{$operation} {$model}", [
            'id' => $id,
            'data' => $data,
            'user_id' => auth()->id() ?? 'guest',
            'ip' => request()->ip(),
        ]);
    }
}