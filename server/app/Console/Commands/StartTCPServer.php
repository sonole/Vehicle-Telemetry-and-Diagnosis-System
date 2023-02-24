<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TCPServerService;

class StartTCPServer extends Command
{
    protected $signature = 'tcp:start';
    protected $description = 'Start the TCP server';
    protected $pidFile = 'tcp_server.pid';

    public function handle()
    {
        if (class_exists('App\Services\TCPServerService')) {
            $tcpServer = new TCPServerService();
            $tcpServer->start();
            file_put_contents($this->pidFile, getmypid());
        } else {
            echo 'Error: TCPServerService class not found.'.PHP_EOL.PHP_EOL;
        }
    }
}
