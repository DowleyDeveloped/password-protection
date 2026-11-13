<?php

namespace dowleydeveloped\passwordprotection\services;

use yii\base\Component;
use craft\db\Query;
use Craft;

class ContentService extends Component
{
    public function getContent(): ?array
    {
        return Craft::$app->cache->getOrSet('password-content', function () {
            return (new Query())
                ->from('{{%dowley_password_content}}')
                ->one() ?: null;
        });
    }
}
