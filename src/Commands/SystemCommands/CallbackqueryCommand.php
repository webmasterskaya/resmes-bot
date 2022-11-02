<?php

namespace Webmasterskaya\ResmesBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands as BotCommands;
use Longman\TelegramBot\Entities\ServerResponse;

class CallbackqueryCommand extends BotCommands\SystemCommands\CallbackqueryCommand
{
	public function execute(): ServerResponse
	{
		$callback_query = $this->getCallbackQuery();

		$data = explode('_', $callback_query->getData());

		$answer = $this->telegram->executeCommand($data[0] . '_' . $data[1]);

		return ($answer instanceof ServerResponse) ? $answer : $callback_query->answer();
	}
}