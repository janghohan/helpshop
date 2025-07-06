<?php
// 환경 설정 (운영 서버에서는 이 값을 직접 'production'으로 고정하거나, ENV 환경변수로 설정)
$env = getenv('APP_ENV') ?: 'production';

$config = [];

if ($env === 'development') {
    $config = [
        'mode' => 'development',
        'python_path' => 'python',
        'unlock_script_path' => './api/unlock_excel.py',
        'change_script_path' => './api/change_excel.py',
    ];
} elseif ($env === 'production') {
    $config = [
        'mode' => 'production',
        'python_path' => '/usr/bin/python3',
        'unlock_script_path' => '/var/www/html/api/unlock_excel.py',
        'change_script_path' => '/var/www/html/api/change_excel.py',
    ];
} else {
    throw new Exception("Unknown environment: $env");
}

return $config;
