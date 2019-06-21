https://sentry.io/onboarding/nexttech-9b/get-started/
composer require sentry/sdk:2.0.3
o capture all errors, even the one during the startup of your application, you should initialize the Sentry PHP SDK as soon as possible.

Sentry\init(['dsn' => 'https://b3713d886aed4cd3afa56ef65572ace7@sentry.io/1487129' ]);
You can trigger a PHP exception by throwing one in your application:

throw new Exception("My first Sentry error!");


https://github.com/notamedia/yii2-sentry
