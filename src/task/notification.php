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

function sendGroupMessage($subject)
{
    $url = get('notify_channel_url');

    if (! $url) {
        throw new \InvalidArgumentException('[Laravel-Deployer]Notification is on but channel url is not set!');
    }

    $notifyBy = get('notify_by', 'webhook');

    switch ($notifyBy) {
        case 'wechat_bot':
            $formParams = [
                'msgtype' => 'news',
                'news' => [
                    'articles' => [
                        [
                            'title' => get('user').' '.$subject,
                            'description' =>  '在 '.get('environment').' 环境更新 '.get('branch').' 分支 ',
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

    return get('group_notify') ? sendHttpRequest($url, $formParams) : writeln($content);
}

task('success:notify', function () {
    return sendGroupMessage('成功发布新版本！');
})->local();

task('failed:notify', function () {
    return sendGroupMessage('发布新版本失败！');
})->local();
