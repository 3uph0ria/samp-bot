<?php

include_once ('config.php');
include_once ('include/simplevk/autoload.php');
include_once ('include/Database/Database.php');
include_once ('include/SampQueryAPI/SampQueryAPI.php');

$sampQueryAPI = new SampQueryAPI(IP, PORT);
$Database = new Database();

use DigitalStar\vk_api\VK_api as vk_api;
use DigitalStar\vk_api\VkApiException;

$vk = vk_api::create(TOKEN, VERSION)->setConfirm(KEY);
$data = json_decode(file_get_contents('php://input'));
$vk->sendOK(); //Говорим vk, что мы приняли callback

$message = mb_strtolower($data->object->message->text, 'utf-8'); // текст сообщения, который отправили боту
$peer_id = $data->object->message->peer_id; // id пользователя/беседы, который отправил сообщение

if($message)
{
    if($message == SERVER_INFO)
    {
        $serverInfo = $sampQueryAPI->getInfo();
        $vk->sendMessage($peer_id,
            'Сервер: ' . mb_convert_encoding($serverInfo['hostname'], "UTF-8", "windows-1251" ) . "\n" .
            'Игроков: ' . $serverInfo['players'] . ' из ' . $serverInfo['maxplayers'] . "\n"
        );
    }

    if($message == PLAYERS_INFO)
    {
        $serverInfo = $sampQueryAPI->getInfo();
        $players = $sampQueryAPI->getDetailedPlayers();
        $list = 'Игроков ' . $serverInfo['players'] . ' из ' . $serverInfo['maxplayers'] . "\n";

        for($i = 0; $i < Count($players); $i++)
        {
            $list .= $i + 1 . '. ' . mb_convert_encoding($players[$i]['nickname'], "UTF-8", "windows-1251" ) . ' Score: ' . $players[$i]['score'] . "\n";
        }

        $vk->sendMessage($peer_id, $list);
    }

    if($message == LOGS)
    {
        $logs = $Database->GetLogs();
        $list = '';

        for($i = 0; $i < Count($logs); $i++)
        {
            $list .= $logs[$i]['idn'] . '. ' . $logs[$i]['Name'] . ' | ' . $logs[$i]['Name2'] . ' | ' . $logs[$i]['Nomer'] . ' | ' . $logs[$i]['Cmd'] . "\n";
        }

        $vk->sendMessage($peer_id, $list);
    }
}
