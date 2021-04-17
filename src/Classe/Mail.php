<?php

namespace App\Classe;
Use Mailjet\Client;
Use Mailjet\Resources;

class Mail{

    private $key_api = 'a933f0f289f64064720fc71e0e1742c7' ;
    private $key_api_secret = '58b20148888027f6382fffe9ce362638';
	
    public function send($to_email, $to_name, $subject,$content){

        $mj = new Client($this->key_api, $this->key_api_secret, true,['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "laracias.oneman@gmail.com",
                        'Name' => "la boutique française"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 2739182,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' =>[
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() ;
    }

    
    



}