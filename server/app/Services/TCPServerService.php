<?php
namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class TCPServerService
{
    protected $socket;

    public function start()
    {
        $this->socket = @stream_socket_server("tcp://0.0.0.0:8282", $errno, $errstr);

        if (!$this->socket) {
            Log::error("Error starting server: $errstr");
            return;
        }

        Log::info("Server started");

        while (true) {
            $clientSocket = stream_socket_accept($this->socket, -1);

            if (!$clientSocket) {
                Log::error("Error accepting client connection");
                continue;
            }

            Log::info("Client connected");

            $clientData = fread($clientSocket, 1024);

            Redis::set('client_data', $clientData);

            Log::info("Received data: $clientData");

            fwrite($clientSocket, "Data received");

            fclose($clientSocket);
        }

        fclose($this->socket);
    }

    public function stop()
    {
        if ($this->socket) {
            fclose($this->socket);
            Log::info("Server stopped");
        }
    }
}
