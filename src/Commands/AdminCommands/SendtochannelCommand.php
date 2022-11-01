<?php

namespace Webmasterskaya\ResmesBot\Commands\AdminCommands;

use Longman\TelegramBot\Commands as BotCommands;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class SendtochannelCommand extends BotCommands\AdminCommands\SendtochannelCommand
{
	/**
	 * Command execute method
	 *
	 * @return ServerResponse
	 */
	public function execute(): ServerResponse
	{
		return Request::emptyResponse();
	}

	/**
	 * Command execute without db method
	 *
	 * @return ServerResponse
	 */
	public function executeNoDb(): ServerResponse
	{
		return Request::emptyResponse();
	}
}