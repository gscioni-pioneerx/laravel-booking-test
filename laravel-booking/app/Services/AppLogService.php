<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AppLogService
{
    /**
     * Prepare log context
     *
     * @param array $context
     * @return array
     */
    protected function prepareContext(array $context = []): array
    {
        $defaultContext = [
            'user_id' => auth()->user()->id
        ];

        return array_merge($defaultContext, $context);
    }

    /**
     * Create emergency level log
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function emergency(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->emergency($message, $context);
    }

    /**
     * Create alert level log
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function alert(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->alert($message, $context);
    }

    /**
     * Create critical level log
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function critical(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->critical($message, $context);
    }

    /**
     * Create error level log
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function error(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->critical($message, $context);
    }

    /**
     * Create warning level log
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function warning(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->warning($message, $context);
    }

    /**
     * Create notice level log
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function notice(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->notice($message, $context);
    }

    /**
     * Create info level log
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function info(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->info($message, $context);
    }

    /**
     * Create debug level log
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function debug(string $message, array $context = []): void
    {
        $context = $this->prepareContext($context);

        Log::channel('application')->debug($message, $context);
    }
}
