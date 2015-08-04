<?php
    namespace Redpic\Antigate\Exceptions;

    class AntigateException extends \Exception // Documentation: http://antigate.com/?action=api#errorcodes
    {
        const ERROR_WRONG_USER_KEY = 1;
        const ERROR_KEY_DOES_NOT_EXIST = 2;
        const ERROR_ZERO_BALANCE = 3;
        const ERROR_NO_SLOT_AVAILABLE = 4;
        const ERROR_ZERO_CAPTCHA_FILESIZE = 5;
        const ERROR_TOO_BIG_CAPTCHA_FILESIZE = 6;
        const ERROR_WRONG_FILE_EXTENSION = 7;
        const ERROR_IMAGE_TYPE_NOT_SUPPORTED = 8;
        const ERROR_IP_NOT_ALLOWED = 9;

        const ERROR_WRONG_ID_FORMAT = 10;
        const ERROR_CAPTCHA_UNSOLVABLE = 11;

        const ERROR_TIMEOUT = 12;

        private static $messages = [
            self::ERROR_WRONG_USER_KEY => 'User authorization key is invalid (its length is not 32 bytes as it should be)',
            self::ERROR_KEY_DOES_NOT_EXIST => 'You have set wrong user authorization key in request',
            self::ERROR_ZERO_BALANCE => 'Account has zero or negative balance',
            self::ERROR_NO_SLOT_AVAILABLE => 'No idle captcha workers are available at the moment',
            self::ERROR_ZERO_CAPTCHA_FILESIZE => 'The size of the captcha you are uploading is zero',
            self::ERROR_TOO_BIG_CAPTCHA_FILESIZE => 'Your captcha size is exceeding 100kb limit',
            self::ERROR_WRONG_FILE_EXTENSION => 'Your captcha file has wrong extension, the only allowed extensions are gif, jpg, jpeg, png',
            self::ERROR_IMAGE_TYPE_NOT_SUPPORTED => 'Could not determine captcha file type, only allowed formats are JPG, GIF, PNG',
            self::ERROR_IP_NOT_ALLOWED => 'Request with current account key is not allowed from your IP',

            self::ERROR_WRONG_ID_FORMAT => 'The captcha ID you are sending is non-numeric',
            self::ERROR_CAPTCHA_UNSOLVABLE => 'Captcha could not be solved by 5 different people',

            self::ERROR_TIMEOUT => 'Timeout',
        ];

        public function __construct($message, $code = null, Exception $previous = null) 
        {
            if (array_key_exists($code, self::$messages)) {
                $message = self::$messages[$code];
            }
            parent::__construct($message, $code, $previous);
        }


    }

