<?php

namespace CodingLiki\WebSocketServer;

class WebSocketClient
{
    public $headers;

    public $serverSocketKey;

    public $handshakeDone;

    public function __construct($handShake)
    {
        $this->headers = [];
        $this->readHandShake($handShake);
        $this->handshakeDone = false;
    }

    public function readHandShake($handShake)
    {
        $headers = explode("\n", $handShake);
        $headers = array_map("trim", $headers);
        $headers = array_filter($headers, function($val){
            return !empty($val);
        });
        $newHeaders = [];
        array_map(function($el) use(&$newHeaders){
            $mass = explode(": ", $el);
            // var_dump($mass);
            if(!isset($mass[1])){
                return;
            }
            if(isset($newHeaders[$mass[0]])){
                if(!isset($newHeaders[$mass[0]]['next'])){
                    $newHeaders[$mass[0]]['next'] = [];
                }
                $newHeaders[$mass[0]]['next'][] = explode(";", $mass[1]);
                $newHeaders[$mass[0]]['next'][] = array_map('trim', $newHeaders[$mass[0]]['next'][]);
            } else {
                $newHeaders[$mass[0]] = explode(";", $mass[1]);
                $newHeaders[$mass[0]] = array_map('trim', $newHeaders[$mass[0]]);
            }
        }, $headers);
        $this->headers = $newHeaders;
    }
}
