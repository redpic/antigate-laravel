# antigate-laravel
antigate.com API Laravel 5.1 package

## Инструкция
Установите пакет: `composer require redpic/antigate`

Добавьте сервис-провайдер в `config/app.php`:
```php
'providers' => [
    // ...
    Redpic\Antigate\AntigateServiceProvider::class,
];
```    
Опубликуйте конфиг: `php artisan vendor:publish` и впишите в него токен доступа: `config/antigate.php`
    
Создайте два слушателя событий:

    php artisan make:listener test --event CaptchaWasRecognized
    php artisan make:listener test --event CaptchaWasNotRecognized
    
Приведите их к виду:

`app/Listeners/CaptchaWasRecognizedListener.php`:
```php
<?php

namespace App\Listeners;

use Redpic\Antigate\Events\CaptchaWasRecognized;
use Redpic\Antigate\Jobs\RecognizeCaptcha;

class CaptchaWasRecognizedListener
{
    public function handle(CaptchaWasRecognized $event)
    {
        $event->captcha->getKey(); // Тут текст разгаданной капчи
    }
}
```
`app/Listeners/CaptchaWasNotRecognizedListener.php`:
```php
<?php

namespace App\Listeners;

use Redpic\Antigate\Events\CaptchaWasNotRecognized;
use Redpic\Antigate\Jobs\RecognizeCaptcha;

class CaptchaWasNotRecognizedListener
{
    public function handle(CaptchaWasNotRecognized $event)
    {
        $event->captcha; // Не разгаданная капча
        $event->exception; // Исключение вызванное во время работы

        /*
        Если в этом месте вызвать какое то исключение, 
        то задание по разгадываю этой капчи снова добавится в очередь
        */
    }
}
```    
Зарегистрируйте слушатели `app/Providers/EventServiceProvider.php`:
```php
protected $listen = [
    //...
    'Redpic\Antigate\Events\CaptchaWasRecognized' => [
        'App\Listeners\CaptchaWasRecognizedListener',
    ],
    'Redpic\Antigate\Events\CaptchaWasNotRecognized' => [
        'App\Listeners\CaptchaWasNotRecognizedListener',
    ],
];
```
Добавление задания из контроллера выглядит примерно так:
```php
$captcha = (new Captcha)->setImageByUrl('http://ПУТЬ_К_КАПЧЕ');
$this->dispatch(new RecognizeCaptcha($captcha));
```
