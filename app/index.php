<?php

use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;
use Webmasterskaya\ResmesBot\Commands\AdminCommands as AdminCommands;
use Webmasterskaya\ResmesBot\Commands\SystemCommands\CallbackqueryCommand;
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
	$telegram->addCommandClass(CallbackqueryCommand::class);

	$telegram->addCommandClass(AdminCommands\ChatsCommand::class);
	$telegram->addCommandClass(AdminCommands\CleanupCommand::class);
	$telegram->addCommandClass(AdminCommands\SendtoallCommand::class);
	$telegram->addCommandClass(AdminCommands\SendtochannelCommand::class);
	$telegram->addCommandClass(AdminCommands\WhoisCommand::class);

	$telegram->addCommandClass(TBC\SystemCommands\RemoveJoinMessagesCommand::class);
//	$telegram->addCommandClass(TBC\SystemCommands\RemovePinnedMessagesCommand::class);
	$telegram->addCommandClass(TBC\SystemCommands\RemoveVoiceVideoChatMessagesCommand::class);

	$telegram->addCommandClass(TBC\UserCommands\AddWatermarkCommand::class);
	$telegram->addCommandClass(TBC\UserCommands\AddWatermarkCallbackqueryCommand::class);
	$telegram->setCommandConfig('addwatermark', [
		'watermark_path' => realpath(dirname(__FILE__, 2) . '/' . ltrim($_ENV['BOT_WATERMARK_PATH'], '/')),
	]);
	$telegram->setCommandConfig('addwatermark_callbackquery', [
		'watermark_path' => realpath(dirname(__FILE__, 2) . '/' . ltrim($_ENV['BOT_WATERMARK_PATH'], '/')),
	]);

	$telegram->setDownloadPath(realpath(dirname(__FILE__, 2) . '/' . trim($_ENV['BOT_DOWNLOAD_PATH'], '/')));

	$telegram->setUpdateFilter(function (Update $update, Telegram $telegram, &$reason = 'Update denied by update_filter') {

		$bot_available_channels = array_map(function ($item) {
			return (int) $item;
		}, explode(',', $_ENV['BOT_AVAILABLE_CHANNELS']));

		if($update->getUpdateType() == Update::TYPE_MESSAGE){
			$chat_id = $update->getMessage()->getChat()->getId();

			if (!empty($bot_available_channels) && !$update->getMessage()->getChat()->isPrivateChat())
			{
				$found = false;

				foreach ($bot_available_channels as $bot_available_channel)
				{
					if ($chat_id === $bot_available_channel)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					$reason = 'This chat is not hosted by a bot';

					return false;
				}
			}
		}



		return true;
	});

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