<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StopTCPServer extends Command
{
    protected $signature = 'tcp:stop';
    protected $description = 'Stop the running instance of the StartTCPServer command';

    public function handle()
    {
        $pidFile = storage_path('app/tcp_server.pid');

        if (!file_exists($pidFile)) {
            $this->error('No running instances of StartTCPServer found.');
            return;
        }

        $pid = file_get_contents($pidFile);

        if (!posix_kill($pid, 0)) {
            $this->error('No running instances of StartTCPServer found.');
            return;
        }

        exec("kill $pid");

        $this->info('The running instance of StartTCPServer has been stopped.');
    }
}
