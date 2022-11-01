<?php

use Webmasterskaya\ResmesBot\Commands\AdminCommands as AdminCommands;
use Webmasterskaya\ResmesBot\Commands\SystemCommands\GenericmessageCommand;
use Webmasterskaya\TelegramBotCommands\Commands as TBC;

require dirname(__FILE__, 2) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 2));
$dotenv->load();

$bot_api_key  = $_ENV['BOT_API_KEY'];
$bot_username = $_ENV['BOT_USERNAME'];
$bot_admins   = array_map(function ($item) {
	return (int) $item;
}, explode(',', $_ENV['BOT_ADMINS']));

try
{
	// Create Telegram API object
	$telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

	$telegram->addCommandClass(GenericmessageCommand::class);

	$telegram->addCommandClass(AdminCommands\ChatsCommand::class);
	$telegram->addCommandClass(AdminCommands\CleanupCommand::class);
	$telegram->addCommandClass(AdminCommands\SendtoallCommand::class);
	$telegram->addCommandClass(AdminCommands\SendtochannelCommand::class);
	$telegram->addCommandClass(AdminCommands\WhoisCommand::class);

	$telegram->addCommandClass(TBC\SystemCommands\RemoveJoinMessagesCommand::class);
	$telegram->addCommandClass(TBC\SystemCommands\RemoveVoiceVideoChatMessagesCommand::class);
//	$telegram->addCommandClass(TBC\SystemCommands\RemovePinnedMessagesCommand::class);

	if (!empty($bot_admins))
	{
		foreach ($bot_admins as $bot_admin)
		{
			$telegram->enableAdmin($bot_admin);
		}
	}

	// Handle telegram webhook request
	$telegram->handle();
}
catch (Longman\TelegramBot\Exception\TelegramException $e)
{
	// Silence is golden!
	// log telegram errors
	// echo $e->getMessage();
}