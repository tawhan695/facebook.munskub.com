<?php
header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_CORS_LIST = [
        "http://localhost:5173",
        "http://localhost:5173/",
        "https://facebook.munskub.com",
        "https://facebook.munskub.com/",
    ];

    if (!in_array($_SERVER['HTTP_ORIGIN'], $_CORS_LIST)) {
        http_response_code(500);
        echo json_encode(array('message' => 'Failed to request OTP'));
        exit;
    }

    $url = 'https://otp.thaibulksms.com/v2/otp/request';

    $data = http_build_query(array(
        'key' => $_POST['key'],
        'secret' => $_POST['token'],
        'msisdn' => $_POST['phone']
    ));

    $options = array(
        'http' => array(
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n".
                         "Accept: application/json\r\n",
            'method'  => 'POST',
            'content' => $data,
        ),
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        http_response_code(500);
        echo json_encode([
            'message' => 'Internal Server Error'
        ]);
    } else {
        echo $result;
    }
}

