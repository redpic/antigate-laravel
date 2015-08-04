# antigate-laravel
antigate.com API Laravel 5.1 package

## Инструкция
Установите пакет: `composer require redpic/antigate`

Добавьте сервис-провайдер в `config/app.php`:

    'providers' => [
        // ...
        Redpic\Antigate\AntigateServiceProvider::class,
    ];
    
Создайте два слушателя событий:

    php artisan make:listener test --event CaptchaWasRecognized
    php artisan make:listener test --event CaptchaWasNotRecognized
    
Приведите их к виду:

`app/Listeners/CaptchaWasRecognizedListener.php`:

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

`app/Listeners/CaptchaWasNotRecognizedListener.php`:

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
            Если в этом месте вызвать какое то исключение, то задание по разгадываю этой капчи снова добавится в очередь
            */
        }
    }
    
Зарегистрируйте слушатели `app/Providers/EventServiceProvider.php`:

    protected $listen = [
        //...
        'Redpic\Antigate\Events\CaptchaWasRecognized' => [
            'App\Listeners\CaptchaWasRecognizedListener',
        ],
        'Redpic\Antigate\Events\CaptchaWasNotRecognized' => [
            'App\Listeners\CaptchaWasNotRecognizedListener',
        ],
    ];

Добавление задания из контроллера выглядит примерно так:

    $captcha = (new Captcha)->setImageByUrl('http://ПУТЬ_К_КАПЧЕ');
    $this->dispatch(new RecognizeCaptcha($captcha));
