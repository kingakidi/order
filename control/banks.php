<?php
    
        include "./conn.php";

        $curl = curl_init();
         curl_setopt_array($curl, array(
         CURLOPT_URL => "https://api.flutterwave.com/v3/banks/ng",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "GET",
         CURLOPT_HTTPHEADER => array(
             "Authorization: Bearer $flutterLiveSecretKey"
         ),
         ));
         $response = curl_exec($curl);

         curl_close($curl);
         $res = json_decode($response, true);
        
        //  echo "<option value=''> Choose Bank </option>";
         foreach ($res['data'] as $banksArray) {
         $bankName = $banksArray['name'];
         $bankCode = $banksArray['code'];
             echo "<option value='$bankCode'> $bankName </option>";
         }
