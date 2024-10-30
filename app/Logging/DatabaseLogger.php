<?php

namespace App\Logging;

use Illuminate\Support\Facades\Auth;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Illuminate\Support\Facades\DB;

class DatabaseLogger
{
    public function __invoke(array $config)
    {
        $logger = new Logger('database');
        $logger->pushHandler(new DatabaseHandler());
        return $logger;
    }
}

class DatabaseHandler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        try {
            $user_id = auth()->check() ? auth()->id() : 0;
            
            DB::table('error_logs')->insert([
                'user_id' => $user_id,
                'channel' => $record['channel'],
                'message' => $record['message'],
                'level' => $record['level'],
                'level_name' => $record['level_name'],
                'unix_time' => $record['datetime']->format('U'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
