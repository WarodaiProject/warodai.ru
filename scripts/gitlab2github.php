<?php
   // Скрипт переноса issue с gitlab на github

   $private_token = '45AiVHixRTarK7tKndSg';

   $url = 'https://gitlab.warodai.ru/api/v4/projects/warodai%2Fwarodai-source/issues';
   $gitlab_issues = json_decode(file_get_contents($url.'?per_page=100&private_token='.$private_token), true);

   usort($gitlab_issues, function($a, $b){
        if ($a['iid'] == $b['iid']) {
            return 0;
        }
        return ($a['iid'] < $b['iid']) ? -1 : 1;
   });

   foreach($gitlab_issues as $gitlab_issue){
       $postfix = "\n\n**Дополнительно**  \nПеренесено из Gitlab  \nАвтор: ".$gitlab_issue['author']['name']."  \nДата: ".$gitlab_issue['created_at'];
        $issue = [        
            'title'=>$gitlab_issue['title'],
            'body'=>$gitlab_issue['description'].$postfix,
            'labels'=>$gitlab_issue['labels']
        ];
        $ch = curl_init( 'https://api.github.com/repos/warodai/bjrd-source/issues' );    
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($issue) ); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: token 4f4f3f6c7ef10e1af4bd4dd499b1e7d7183bfda1',
            'User-Agent: warodai',
            'Content-Type: application/json'  
        )); 

        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_HEADER, true );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        $response = curl_exec( $ch );
        $status = curl_getinfo( $ch ); 
        $header = trim(substr($response, 0, $status['header_size']));
        $body = substr($response, $status['header_size']);
        print('Issue '.$gitlab_issue['iid']." added\n");
        print($body."\n");

        if($gitlab_issue['state'] == 'closed'){
            $_t = json_decode($body, true)['number'];

            $ch = curl_init( 'https://api.github.com/repos/warodai/bjrd-source/issues/'.$_t );    
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['state'=>'closed']) ); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: token 4f4f3f6c7ef10e1af4bd4dd499b1e7d7183bfda1',
                'User-Agent: warodai',
                'Content-Type: application/json'  
            )); 

            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
            curl_setopt( $ch, CURLOPT_HEADER, true );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

            $response = curl_exec( $ch );
            $status = curl_getinfo( $ch ); 
            $header = trim(substr($response, 0, $status['header_size']));
            $body = substr($response, $status['header_size']);

            print('Issue '.$gitlab_issue['iid']." closed\n");
            print($body."\n");

        }

        sleep(3);
   }
   