<?php

namespace Redpic\Antigate\Events;

use Illuminate\Queue\SerializesModels;
use Redpic\Antigate\Captcha;

class CaptchaWasRecognized
{
    use SerializesModels;

    public $captcha;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Captcha $captcha)
    {
        $this->captcha = $captcha;
    }
}
