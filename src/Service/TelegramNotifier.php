<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TelegramNotifier
{
	private string $token;
	private string $chatId;

	public function __construct(
		HttpClientInterface $client,
		string $telegramBotToken,
		string $telegramChatId
	) {
		$this->client = $client;
		$this->token = $telegramBotToken;
		$this->chatId = $telegramChatId;
	}

	private HttpClientInterface $client;

	public function send(string $message): void
	{
		$this->client->request('POST', sprintf(
			'https://api.telegram.org/bot%s/sendMessage',
			$this->token
		), [
			'json' => [
				'chat_id' => $this->chatId,
				'text' => $message,
				'parse_mode' => 'HTML',
				'disable_web_page_preview' => true,
			],
			'timeout' => 5,
		]);
	}
}
