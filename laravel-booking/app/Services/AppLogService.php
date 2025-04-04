<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AppLogService
{
    /**
     * Prepare log context
     */
    protected function prepareContext(array $context = []): array
    {
        $defaultContext = [
            'user_id' => auth()->user()->id,
        ];

        return array_merge($defaultContext, $context);
    }

    /**
     * Create emergency level log
     */
    public function emergency(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->emergency($message, $context);
    }

    /**
     * Create alert level log
     */
    public function alert(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->alert($message, $context);
    }

    /**
     * Create critical level log
     */
    public function critical(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->critical($message, $context);
    }

    /**
     * Create error level log
     */
    public function error(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->critical($message, $context);
    }

    /**
     * Create warning level log
     */
    public function warning(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->warning($message, $context);
    }

    /**
     * Create notice level log
     */
    public function notice(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->notice($message, $context);
    }

    /**
     * Create info level log
     */
    public function info(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->info($message, $context);
    }

    /**
     * Create debug level log
     */
    public function debug(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->debug($message, $context);
    }
}
