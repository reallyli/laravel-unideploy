<?php

namespace Deployer;

/**
 * @param string $content
 * @return mixed
 */
function sendGroupNotify(string $content)
{
    $data = json_encode(['text' => $content]);
    $ch = curl_init(get('notify_channel_url'));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]
    );

    return curl_exec($ch);
}

task('success:notify', function () {
    $successMessage = join("\n", [
        "\n 🔚🔚Successfully released",
        'environment: ' . get('environment'),
        'announcer: ' . get('user'),
        'branch: ' . get('branch'),
        'application: ' . get('application')
    ]);

    get('group_notify') ? sendGroupNotify($successMessage) : writeln($successMessage);
})->local();

task('failed:notify', function () {
    $failedMessage = join("\n", [
        "\n 🔙🔙Failed to release",
        'environment: ' . get('environment'),
        'announcer: ' . get('user'),
        'branch: ' . get('branch'),
        'application: ' . get('application')
    ]);

    get('group_notify') ? sendGroupNotify($failedMessage) : writeln($failedMessage);
})->local();




