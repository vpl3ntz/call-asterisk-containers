<?php

namespace App\Http\Controllers;


use PAMI\Client\Exception\ClientException;
use PAMI\Client\Impl\ClientImpl;
use PAMI\Message\Action\OriginateAction;
use PAMI\Message\Action\LogoffAction;


class ClicktocallController extends Controller
{
    private const PAMI_CLIENT_OPTIONS  = [
        'host' => '127.0.0.1',
        'scheme' => 'tcp://',
        'port' => '5038',
        'username' => 'vpbrasilfone' ,
        'secret' => 'vpbsf',
        'connect_timeout' => 60000,
        'read_timeout' => 60000
    ];
    public function ctc($extension, $number): string
    {
        try {
            $connection = new ClientImpl(self::PAMI_CLIENT_OPTIONS);
            $connection->open();

            $action = new OriginateAction('SIP/'.$extension.'/'.$number);
            $action->setContext('kamailio');
            $action->setExtension($number);
            $action->setPriority('1');

            $connection->send($action);
            $connection->send(new LogoffAction());
            $connection->close();
            return "Connection with success";
        } catch (ClientException $clientException) {
            return $clientException->getMessage();
        }
    }
}
