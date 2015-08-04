<?php 
    return [
        'key' => '', // API key
        'api_server' => 'antigate.com',
        'wait' => 5,
        'wait2' => 3,
        'timeout' => 20,
        'captcha' => [
            'is_phrase' => 0,    // 0 OR 1 - captcha has 2 or more words
            'is_regsense' => 0,  // 0 OR 1 - captcha is case sensetive
            'is_numeric' => 0,   // 0 OR 1 - captcha has digits only
            'min_len'  => 0,     // 0 is no limit, an integer sets minimum text length
            'max_len' => 0,      // 0 is no limit, an integer sets maximum text length
            'is_russian' => 0,   // 0 OR 1 - with flag = 1 captcha will be given to a Russian-speaking worker
        ],
    ];