<?php
/**
 * User: MB
 * Date: 9/20/2019
 */


return [

    //maximum number of try for each end point
    'max_try' => 3,
    // format of data send from webhook server
    'response_format' => 'JSON',
    // send request through ssl
    'ssl' => false,
    // maximum seconds try
    'timeout' => 10,
    // number of parallel request
    'concurrency' => 10
];
