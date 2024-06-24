<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Swoole\WebSocket\Server;

class WebSocketCommand extends BaseCommand
{
    protected $group       = 'custom';
    protected $name        = 'start:websocket';
    protected $description = 'Starts the WebSocket server using Swoole.';

    protected $clients     = []; // 클라이언트 fd와 user_id 매핑
    protected $rooms       = []; // room_id와 클라이언트 fd 매핑
    protected $server_url  = "https://api.brav.co.kr/comm/chat/";

    public function run(array $params)
    {
        $server = new Server("0.0.0.0", 8910);

        $server->on('open', function ($server, $req) {
            CLI::write("Connection open: {$req->fd}", 'green');
        });

        $server->on('message', function ($server, $frame) {
            $data = json_decode($frame->data, true);
            $headers = $data['headers'] ?? [];
            $action = $data['action'] ?? null;
            $authorization = $headers['Authorization'] ?? '';
            $room_id = $data['room_id'] ?? null;

            if (!$action) {
                CLI::write("Invalid message format", 'red');
                $server->push($frame->fd, json_encode(['status' => 400, 'error' => 400, 'message' => 'Invalid message format']));
                return;
            }

            $response = $this->handleAction($action, $data, $headers, $frame->fd, $server, $authorization, $room_id);

            $encodedResponse = json_encode($response);

            if (json_last_error() !== JSON_ERROR_NONE) {
                CLI::write('JSON encode error: ' . json_last_error_msg(), 'red');
                $encodedResponse = json_encode(['status' => 500, 'error' => 500, 'message' => 'Internal server error']);
            }

            $server->push($frame->fd, $encodedResponse);
        });

        $server->on('close', function ($server, $fd) {
            CLI::write("Connection close: {$fd}", 'red');
            $this->removeClientFromRooms($fd);
            unset($this->clients[$fd]);
        });

        CLI::write("WebSocket server started on port 8910", 'blue');
        $server->start();
    }

    private function handleAction($action, $data, $headers, $fd, $server, $authorization, $room_id)
    {
        switch ($action) {
            case 'getMyRooms':
                $response = $this->sendHttpRequest($this->server_url . 'roomLists', $data, $authorization, false);
                $this->clients[$fd] = ['fd' => $fd, 'authorization' => $authorization, 'action' => 'getMyRooms'];
                return ['status' => 200, 'data' => $response['data']];
            case 'sendMessage':
                if (!$room_id) {
                    return ['status' => 400, 'message' => 'Room ID is required'];
                }
                $response = $this->sendHttpRequest($this->server_url . 'sendMessage', $data, $authorization);
                $this->notifyClients($server, $authorization);
                $this->broadcastMessageToRoom($server, $room_id, $response, $fd);
                return $response;
            case 'joinRoom':
                if (!$room_id) {
                    return ['status' => 400, 'message' => 'Room ID is required'];
                }
                $this->clients[$fd] = ['fd' => $fd, 'authorization' => $authorization, 'action' => 'joinRoom', 'room_id' => $room_id];
                $this->rooms[$room_id][$fd] = $fd;
                return ['status' => 200, 'message' => 'Joined room', 'fd' => $fd];
            default:
                return ['status' => 400, 'message' => 'Unknown action'];
        }
    }

    private function notifyClients($server, $authorization)
    {
        foreach ($this->clients as $client) {
            if ($client['authorization'] === $authorization && $client['action'] === 'getMyRooms') {
                $data = ['action' => 'getMyRooms', 'headers' => ['Authorization' => $authorization]];
                $response = $this->sendHttpRequest($this->server_url . 'roomLists', $data, $authorization, false);

                if (isset($response['data'])) {
                    $server->push($client['fd'], json_encode(['status' => 200, 'data' => $response['data']]));
                }
            }
        }
    }

    private function broadcastMessageToRoom($server, $room_id, $data, $sender_fd)
    {
        if (isset($this->rooms[$room_id])) {
            foreach ($this->rooms[$room_id] as $fd) {
                if ($fd !== $sender_fd) {
                    $server->push($fd, json_encode(['status' => 200, 'data' => $data]));
                }
            }
        }
    }

    private function removeClientFromRooms($fd)
    {
        foreach ($this->rooms as $room_id => $clients) {
            if (isset($clients[$fd])) {
                unset($this->rooms[$room_id][$fd]);
                if (empty($this->rooms[$room_id])) {
                    unset($this->rooms[$room_id]);
                }
            }
        }
    }

    private function sendHttpRequest($url, $data, $authorization, $is_post = true)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($is_post) {
            curl_setopt($ch, CURLOPT_POST, true);
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: ' . $authorization
        ]);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            CLI::write('Curl error: ' . curl_error($ch), 'red');
            curl_close($ch);
            return ['status' => 500, 'message' => 'Internal server error'];
        }

        curl_close($ch);

        $decodedResponse = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            CLI::write('JSON decode error: ' . json_last_error_msg(), 'red');
            return ['status' => 500, 'message' => 'Internal server error'];
        }

        return $decodedResponse;
    }
}
