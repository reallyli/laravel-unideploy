<?php

namespace Deployer;

function sendHttpRequest($url, $formParams)
{
    $formParams = json_encode($formParams);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $formParams);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        [
            'Content-Type: application/json',
            'Content-Length: '.strlen($formParams),
        ]
    );

    return curl_exec($ch);
}

function sendDeployNotification($subject)
{
    $url = get('notify_channel_url');

    if (! $url) {
        throw new \UnexpectedValueException('[Laravel-Deployer] Not found webhook url!');
    }

    $notifyBy = get('notify_by', 'webhook');

    switch ($notifyBy) {
        case 'wechat_bot':
            $content = '在 '.get('environment').' 环境更新 '.get('branch').' 分支 ';
            $formParams = [
                'msgtype' => 'news',
                'news' => [
                    'articles' => [
                        [
                            'title' => get('user').' '.$subject,
                            'description' => $content,
                            'url' => get('app_repo_url', 'https://github.com'),
                            'picurl' => get('pic_url', 'https://picsum.photos/id/'.rand(1, 1000).'/800/600'),
                        ],
                    ],
                ],
            ];
            break;
        default:
            $content = implode("\n", [
                $subject,
                '应用名称: '.get('application'),
                '发布者: '.get('user'),
                '分支名: '.get('branch'),
                '环境: '.get('environment'),
            ]);
            $formParams = ['text' => $content];
            break;
    }

    if (get('group_notify')) {
        sendHttpRequest($url, $formParams);
    }

    $deployedWebookUrl = get('deployed_webhook_url');

    if ($deployedWebookUrl) {
        $deployedData = [
            'application' => get('application'),
            'user' => get('user'),
            'branch' => get('branch'),
            'environment' => get('environment'),
            'app_repo_url' =>  get('app_repo_url', 'https://github.com'),
        ];
        sendHttpRequest($deployedWebookUrl, $deployedData);
    }

    return writeln($content);
}

task('success:notify', function () {
    return sendDeployNotification('发布 '.get('application').' 项目新版本成功！');
})->local();

task('failed:notify', function () {
    return sendDeployNotification('发布 '.get('application').' 项目新版本失败！');
})->local();
