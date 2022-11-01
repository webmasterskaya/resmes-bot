<?php

require dirname(__FILE__, 2) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 2));
$dotenv->load();

$bot_api_key     = $_ENV['BOT_API_KEY'];
$bot_username    = $_ENV['BOT_USERNAME'];
$bot_webhook_url = $_ENV['BOT_WEBHOOK_URL'];

try
{
	// Create Telegram API object
	$telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

	// Set webhook
	$result = $telegram->setWebhook($bot_webhook_url);
	if ($result->isOk())
	{
		echo $result->getDescription();
	}
}
catch (Longman\TelegramBot\Exception\TelegramException $e)
{
	// log telegram errors
	// echo $e->getMessage();
}