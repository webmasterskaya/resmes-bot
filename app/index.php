<?php

require dirname(__FILE__, 2) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 2));
$dotenv->load();

$bot_api_key  = $_ENV['BOT_API_KEY'];
$bot_username = $_ENV['BOT_USERNAME'];

try
{
	// Create Telegram API object
	$telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

	// Handle telegram webhook request
	$telegram->handle();
}
catch (Longman\TelegramBot\Exception\TelegramException $e)
{
	// Silence is golden!
	// log telegram errors
	// echo $e->getMessage();
}