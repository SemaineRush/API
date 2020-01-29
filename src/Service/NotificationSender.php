<?php

namespace App\Service;

use OneSignal\Config;
use OneSignal\OneSignal;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Component\HttpClient\Psr18Client;

class NotificationSender
{



    private $config;

    public function __construct()
    {
        $this->config =  new Config('d66405ab-347d-4c80-9ecd-2f5684f0f55b', 'ZjQzZmRmMzktODNkNS00NDU5LWE5MTUtN2E1Yjg4NjBhODU2', 'MDlmMjJmZDEtMzNlZi00YjNjLTlkNGItMTM3ZmFlNmE2ZjA0');
        $this->httpClient = new Psr18Client();
    }


    public function sendNotif($message, $date)
    {
        $requestFactory = $streamFactory = new Psr17Factory();
        $oneSignal = new OneSignal($this->config, $this->httpClient, $requestFactory, $streamFactory);
        $oneSignal->notifications()->add([
            'contents' => [
                'en' => $message
            ],
            // 'send_after' => $date,
            'included_segments' => ['All'],
            'data' => ['foo' => 'bar'],
            'isAnyWeb' => true,
        ]);
    }
}
