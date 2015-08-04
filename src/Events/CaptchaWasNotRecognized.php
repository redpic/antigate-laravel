<?php

namespace Redpic\Antigate\Events;

use Illuminate\Queue\SerializesModels;
use Redpic\Antigate\Captcha;

class CaptchaWasNotRecognized
{
    use SerializesModels;

    public $captcha;
    public $exception;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Captcha $captcha, \Exception $exception)
    {
        $this->captcha = $captcha;
        $this->exception = $exception;
    }
}
