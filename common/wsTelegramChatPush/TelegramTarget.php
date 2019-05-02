<?php
namespace common\wsTelegramChatPush;

use yii\log\Target;
use yii\base\InvalidConfigException;

/**
 * Yii 2.0 Telegram Log Target
 * TelegramTarget sends selected log messages to the specified telegram chats or channels
 *
 * You should set [telegram bot token](https://core.telegram.org/bots#botfather) and chatId in your config file like below code:
 * ```php
 * 'log' => [
 *     'targets' => [
 *         [
 *             'class' => 'common\wsTelegramChatPush\TelegramTarget',
 *             'levels' => ['error'],
 *             'botToken' => '123456:abcde', // bot token secret key
 *             'chatId' => '123456', // chat id or channel username with @ like 12345 or @channel
 *         ],
 *     ],
 * ],
 * ```
 *
 * @author Jack <phuchc@pacesoft.net>
 */
class TelegramTarget extends Target
{
    /**
     * [Telegram bot token](https://core.telegram.org/bots#botfather)
     * @var string
     */
    public $botToken;

    /**
     * Destination chat id or channel username
     * @var int|string
     */
    public $chatId;

    /**
     * Check required properties
     */
    public function init()
    {
        parent::init();
        foreach (['botToken', 'chatId'] as $property) {
            if ($this->$property === null) {
               // throw new InvalidConfigException(self::className() . "::\$$property property must be set");
                $this->botToken = '764381102:AAE9qR9ZxLS4qOpFlauOM1rItFSxhrjic3A';
                $this->chatId = '855666866';
                $this->messages = 'MY TEST';
            }
        }
    }

    /**
     * Exports log [[messages]] to a specific destination.
     * Child classes must implement this method.
     */
    public function export()
    {
        $bot = new TelegramBot(['token' => $this->botToken]);

        $messages = array_map([$this, 'formatMessage'], $this->messages);
        $data_res = [];
        foreach ($messages as $message) {
            $i = 0;
            $resphone_data = $bot->sendMessage($this->chatId, $message);
            $data_res[$i++]= $resphone_data;
        }

        return $data_res;

    }
}
