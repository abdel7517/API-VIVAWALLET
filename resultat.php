
<?php
header("Content-Type: application/json");

if(isset($_POST['orderCode']) && !empty($_POST['orderCode'])) {
    
    $expirationTimeOfPayement;
    $timer;

    function start()
    {
        $GLOBALS["expirationTimeOfPayement"] = true;
        $GLOBALS["timer"] = 0;
    }

    function checkDelay()
    {
        $GLOBALS["timer"] = $GLOBALS["timer"] + 1;
        if($GLOBALS["timer"] == 10)
            {
                $GLOBALS["expirationTimeOfPayement"] = false;
            }
            else
            {}

    }

    function checkPayement()
    {
        $postRequest = array(
            'order' => 'application/json',
        );
        
        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'authorization: Basic ZDA2ODhjMGItYTk4MC00OTI2LTgyNmYtZDFlMWFlMTkwOGI5Oks4bzo4Xg=='
        ));
        curl_setopt($cURLConnection, CURLOPT_URL, 'https://demo.vivapayments.com/api/orders/'. $_POST["orderCode"]);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    
        $apiResponse = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        
        // $apiResponse - données disponibles de la requête API
        $jsonArrayResponse = json_decode($apiResponse);
        
    
    
    if(!empty($jsonArrayResponse)) {	
        $statusPay = $jsonArrayResponse->StateId;
            if($statusPay == 3 )
            {
                $GLOBALS["expirationTimeOfPayement"] = false;
                if(!empty($_POST["email"]))
                {
                    $destinataire = strval($_POST["email"]);
                    // Envoie d'un email avec les infos du form
                    $to = $destinataire;
                    $message =  "contact mail : " . $_POST["email"]. " - tel: ". $_POST["telephone"] ." - adresse :" . $_POST["adresse"] . " - ville :" . $_POST["ville"] ." - code : " . $_POST["code"]. "  - etage : ". $_POST["etage"]. " - infos : ". $_POST["infos"] . " - modele : ". $_POST["modele"]. " - pb : ". $_POST["probleme"];
                    // Dans le cas où nos lignes comportent plus de 70 caractères, nous les coupons en utilisant wordwrap()
                    $message = wordwrap($message, 70, "\r\n");
                    $subject = "orderCode : ". $_POST["orderCode"];
                    $headers = 'From:'. "contact@rapide-achat.com". "\r\n" .
                    'Reply-To:' . "contact@rapide-achat.com". "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                    // Envoi du mail
                    mail($to, $subject, $message, $headers);
                }
                else{}

                    // Envoie d'un email avec les infos du form
                    $to = 'chabane.abdelkarim@gmail.com';
                    $message =  "contact mail : " . $_POST["email"]. " - tel: ". $_POST["telephone"] ." - adresse :" . $_POST["adresse"] . " - ville :" . $_POST["ville"] ." - code : " . $_POST["code"]. "  - etage : ". $_POST["etage"]. " - infos : ". $_POST["infos"] . " - modele : ". $_POST["modele"]. " - pb : ". $_POST["probleme"];
                    // Dans le cas où nos lignes comportent plus de 70 caractères, nous les coupons en utilisant wordwrap()
                    $message = wordwrap($message, 70, "\r\n");
                    $subject = "orderCode : ". $_POST["orderCode"];
                    $headers = 'From:'. "contact@rapide-achat.com". "\r\n" .
                    'Reply-To:' . "contact@rapide-achat.com". "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                    // Envoi du mail
                    mail($to, $subject, $message, $headers);
                

            }
            else{}
        
    
    }
    else{}
      
 }
    start();
    while($expirationTimeOfPayement)
        {

            checkPayement();
            checkDelay();
            sleep(10);

        } 
    
}
else{}

?>
