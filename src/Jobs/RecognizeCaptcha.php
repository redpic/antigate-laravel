<?php

namespace Redpic\Antigate\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Event;
use Illuminate\Bus\Queueable;
use GuzzleHttp\Client as GuzzleClient;
use Redpic\Antigate\Exceptions\AntigateException;
use Redpic\Antigate\Events\CaptchaWasRecognized;
use Redpic\Antigate\Events\CaptchaWasNotRecognized;
use Redpic\Antigate\Captcha;

class RecognizeCaptcha implements SelfHandling, ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $captcha;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Captcha $captcha)
    {
        $this->captcha = $captcha;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $client = new GuzzleClient(['base_uri' => 'http://' . config('antigate.api_server')]);

            $response = $client->post('in.php', [
                'multipart' => [
                    [
                        'name' => 'body', 
                        'contents' => $this->captcha->getImage()
                    ]
                ], 
                'query' => array_merge(['method' => 'base64', 'key' => config('antigate.key')], config('antigate.captcha'))]);
            
            $status = $response->getBody();
            $captcha_id = null;
            if (strpos($status, 'OK') === 0) {
                list($status, $captcha_id) = explode('|', $status);
            } elseif (strpos($status, 'ERROR') === 0) {
                throw new AntigateException(null, constant(AntigateException::class . '::' . $status));
            }

            sleep(config('antigate.wait'));
            $text = '';
            $waitTime = 0;
            $wait2 = config('antigate.wait2');
            $timeout = config('antigate.timeout');
            while(true)
            {
                $response = $client->get('res.php', ['query' => ['key' => config('antigate.key'), 'action' => 'get' , 'id' => $captcha_id]]);
                $status = $response->getBody();

                if (strpos($status, 'ERROR') === 0) {
                    throw new AntigateException(null, constant(AntigateException::class . '::' . $status));
                } elseif ($status == "CAPCHA_NOT_READY") {
                    if ($waitTime > $timeout) {
                        throw new AntigateException(null, AntigateException::ERROR_TIMEOUT);
                    }
                    $waitTime += $wait2;
                    sleep($wait2);
                } elseif (strpos($status, 'OK') === 0) {
                    list($status, $text) = explode('|', $status);
                    $this->captcha->setKey($text);
                    Event::fire(new CaptchaWasRecognized($this->captcha));
                    break;
                }
            }
        } catch (\Exception $ex) {
            Event::fire(new CaptchaWasNotRecognized($this->captcha, $ex));
        }
    }
}
