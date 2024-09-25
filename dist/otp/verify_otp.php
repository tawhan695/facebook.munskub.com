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

    $url = 'https://otp.thaibulksms.com/v2/otp/verify';

    $data = http_build_query(array(
        'key' => $_POST['key'],
        'secret' => $_POST['token'],
        'token' => $_POST['otp_ref'],
        'pin' => $_POST['otp']
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
        exit;
    }

    $response = json_decode($result, true);
    if ($response) {
        if ($response['status'] == 'success') {
            http_response_code(200);
            echo $result;
        } else {
            http_response_code(400);
            echo json_encode([
                'message' => 'Invalid OTP code',
                'errors' => $response['errors'] ?? []
            ]);
        }
    } else {
        http_response_code(500);
        echo json_encode([
            'message' => 'Internal Server Error',
            'response' => $response
        ]);
    }

    // else {
    //     echo $result;
    // }

    // if (!in_array($_SERVER['HTTP_ORIGIN'], $_CORS_LIST)) {
    //     http_response_code(403);
    //     echo json_encode(['message' => 'CORS policy violation']);
    // } else{
    //     $response = json_decode($result, true);
    //     if ($response) {
    //         if($response['status'] == 'success'){
    //             http_response_code(200);
    //             echo $result;
    //         } else {
    //             http_response_code(400);
    //             echo json_encode({
    //                 'message' => 'Invalid OTP code',
    //                 'errors' => $response['errors'] ?? []
    //             });
    //         }
    //     } else {
    //         http_response_code(500);
    //         echo json_encode([
    //             'message' => 'Internal Server Error',
    //             'response' => $response
    //         ]);
    //     }
    // }
}
