<?php

namespace Webmasterskaya\ResmesBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands as BotCommands;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class GenericmessageCommand extends BotCommands\SystemCommands\GenericmessageCommand
{
	public function execute(): ServerResponse
	{
		$this->executeAlways();

		return parent::execute();
	}

	public function executeNoDb(): ServerResponse
	{
		$this->executeAlways();

		return parent::executeNoDb();
	}

	public function executeAlways(): ServerResponse
	{
//		$message = $this->getMessage() ?: $this->getEditedMessage() ?: $this->getChannelPost() ?: $this->getEditedChannelPost();
//		ob_start();
//		echo "<pre>";
//		var_dump($message);
//		echo "</pre>";

		$this->replyToChat(ob_get_clean(), ['parse_mode' => 'HTML']);

		$this->telegram->executeCommand('removejoinmessages');
		$this->telegram->executeCommand('removevoicevideochatmessages');

//		$this->telegram->executeCommand('removepinnedmessages');

		return Request::EmptyResponse();
	}
}