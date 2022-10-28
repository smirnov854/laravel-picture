<?php


namespace App\Services;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Mailjet\Client;
use \Mailjet\Resources;

class MailService
{
    public function send($template = '', $data = '', $to = '', $name = '', $subject = '')
    {
        try {
            $mj = new Client(
                env('MAILJET_API_KEY'),
                env('MAILJET_SECRET_KEY'), true
                ,
                [
                    'version' => 'v3.1',
                    'proxy' => 'socks5://TaVbAw:HR0Jtr@45.130.62.159:8000'
                ]
            );
            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => env('MAILJET_EMAIL_FROM'),
                            'Name' => "test"
                        ],
                        'To' => [
                            [
                                'Email' => $to,
                                'Name' => $name
                            ]
                        ],
                        'Subject' => $subject,
                        'TextPart' => $data,
                        //'HtmlPart' => view($template, $data)->render()
                    ]
                ]
            ];
            $response = $mj->post(Resources::$Email, ['body' => $body]);
            //$response->success() && var_dump($response->getData());
        } catch (\Exception $e) {
            Log::info('mail send problem -- ' . $e->getMessage());
        }
    }
}
