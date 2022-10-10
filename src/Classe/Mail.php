<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
  private $api_key = '096b245db057c6f768440d7abec23fca';
  private $api_key_secret = 'c6f94541b07442746f199a295615c05b';

  public function send($to_email, $to_name, $subject, $content)
  {
    $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);
    $body = [
      'Messages' => [
        [
          'From' => [
            'Email' => "daub.ludovic@gmail.com",
            'Name' => "STUDI SPORT"
          ],
          'To' => [
            [
              'Email' => $to_email,
              'Name' => $to_name
            ]
          ],
          'TemplateID' => 4176231,
          'TemplateLanguage' => true,
          'Subject' => $subject,
          'Variables' => [
            'content' => $content
          ]
        ]
      ]
    ];
    $response = $mj->post(Resources::$Email, ['body' => $body]);
    $response->success();
  }
}
