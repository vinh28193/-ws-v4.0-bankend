<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 7/7/2018
 * Time: 8:29 AM
 */

namespace common\i18n;

class TranslationEventHandler
{
    public static function handleMissingTranslation(\yii\i18n\MissingTranslationEvent $event)
    {

        $message = new Message([
            'language' => $event->language,
            'translation' => $event->message
        ]);
        if (($sourceMessage = self::findSourceMessage($event->message, $event->category)) !== null) {
            $message->id = $sourceMessage->id;
        } else {
            $sourceMessage = new SourceMessage();
            $sourceMessage->message = $event->message;
            $sourceMessage->category = $event->category;
            $sourceMessage->save();
            $message->id = $sourceMessage->id;
        }
        if ($message->validate()) {
            $message->save();
        }
        $event->translatedMessage = $event->message;
    }

    protected static function findSourceMessage($message, $category)
    {
        return SourceMessage::find()->where(['AND',['message' => $message],['category' => $category]])->one();
    }
}