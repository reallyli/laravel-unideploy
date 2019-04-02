<?php

namespace Deployer;

/**
 * @param string $content
 * @throws \Exception
 * @return mixed
 */
function sendGroupNotify(string $content)
{
    if (! get('notify_channel_url')) {
        throw new \InvalidArgumentException('[Laravel-Deployer]Notification is on but channel url is not set!');
    }

    $data = json_encode(['text' => $content]);
    $ch = curl_init(get('notify_channel_url'));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        [
            'Content-Type: application/json',
            'Content-Length: '.strlen($data),
        ]
    );

    return curl_exec($ch);
}

task('success:notify', function () {
    $successMessage = implode("\n", [
        'Successfully released 🚀🎉🎊 ',
        'application: '.get('application'),
        'announcer: '.get('user'),
        'branch: '.get('branch'),
        'environment: '.get('environment'),
    ]);

    get('group_notify') ? sendGroupNotify($successMessage) : writeln($successMessage);
})->local();

task('failed:notify', function () {
    $failedMessage = implode("\n", [
        'Failed to release 🚀👻⚡',
        'application: '.get('application'),
        'announcer: '.get('user'),
        'branch: '.get('branch'),
        'environment: '.get('environment'),
    ]);

    get('group_notify') ? sendGroupNotify($failedMessage) : writeln($failedMessage);
})->local();
