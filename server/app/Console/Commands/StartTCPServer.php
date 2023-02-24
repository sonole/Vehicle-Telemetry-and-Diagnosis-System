<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TCPServerService;

class StartTCPServer extends Command
{
    protected $signature = 'tcp:start';
    protected $description = 'Start the TCP server';
    protected $pidFile = 'logs/tcp_server.pid';

    public function handle()
    {
        if (class_exists('App\Services\TCPServerService')) {
            $tcpServer = new TCPServerService();
            file_put_contents(storage_path($this->pidFile), getmypid());
            $tcpServer->start();
        } else {
            echo 'Error: TCPServerService class not found.'.PHP_EOL;
        }
    }
}
