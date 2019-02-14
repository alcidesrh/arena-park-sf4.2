<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostFinanceController extends AbstractController
{
    public $postFinance = 'e-payment.postfinance.ch';

    // Host used
    public		$host = 'secure.ogone.com';

    // Ogone identifier
    public		$pspid = 'arenapark';

    // Secret SHA1 to encrypt data sent to Ogone (DO NOT MODIFY)
    public		$in_secret = 'arRd2$2239sadm!!d';

    // Secret SHA1 To decrypt data sent by Ogone (DO NOT MODIFY)
    public		$out_secret = 'arRd2$2239sadm!!d';

    // Code ISO a 3 CURRENCY CODE
    public		$currency_iso = 'CHF';

    // Langue
    public		$language_iso = 'fr_FR';

    // template url
    public		$template_url;

    // URL used when the user click on the button BACK TO MERCHANT
    public		$home_url = 'https://www.arena-park.ch/';

    // URL used when the user click on the button  "back to catalog"
    public		$catalog_url = 'https://www.arena-park.ch/reservation-park.php';

    // URL used when the user has paid with Ogone
    public		$accept_url = 'https://www.arena-park.ch/phpFiles/psp/post_payment.php';

    // Environment used (test ou prod)
    public		$env = 'prod';

    static public	$human_status = array(
        0 => "Incomplet ou invalide",
        1 => "Annulé par client",
        2 => "Autorisation refusée",
        4 => "Commande encodée",
        40 => "",
        41 => "Attente paiement par client",
        5 => "Autorisé",
        50 => "",
        51 => "Autorisation en attente",
        52 => "Autorisation incertaine",
        55 => "En suspens",
        56 => "OK avec paiements planifiés",
        57 => "Erreur dans les paiements planifiés",
        59 => "Autor. à obtenir manuellement",
        6 => "Autorisé et annulé",
        61 => "Annul. d'autor. en attente",
        62 => "Annul. d'autor. incertaine",
        63 => "Annul. d'autor. refusée",
        64 => "Autorisé et annulé",
        7 => "Paiement annulé",
        71 => "Annulation paiement en attente",
        72 => "Annul paiement incertaine",
        73 => "Annul paiement refusée",
        74 => "Paiement annulé",
        75 => "Annulation traitée par le marchand",
        8 => "Remboursement",
        81 => "Remboursement en attente",
        82 => "Remboursement incertain",
        83 => "Remboursement refusé",
        84 => "Remboursement",
        85 => "Rembours. traité par le marchand",
        9 => "Paiement demandé",
        91 => "Paiement en cours",
        92 => "Paiement incertain",
        93 => "Paiement refusé",
        94 => "Remb. Refusé par l'acquéreur",
        95 => "Paiement traité par le marchand",
        96 => "",
        99 => "En cours de traitement"
    );


    public function getUrlRedirect(Reservation $reservation, $paymentData){

        $this->host = $this->postFinance;
        // todo: REPLACE WITH THE TOTAL AMOUNT OF THE ORDER (ex: 120.50)
//				$amount = $_REQUEST['totalreal'];
//        $amount = $_REQUEST['totalreal'];// with 10%
//        $amount = $amount * 0.077 + $amount;
//        $amount = round($amount, 2);
        $url = $this->GetInitPaymentURL($reservation->getId(), $reservation->getPayment(), array_merge($paymentData, array(
            'ECOM_BILLTO_POSTAL_NAME_FIRST' => $reservation->getUser()->getName(), // Optional customer first name
//            'ECOM_BILLTO_POSTAL_NAME_LAST' => utf8_decode($user['last_name']), // Optional customer last name
            'EMAIL' => $reservation->getUser()->getEmail(), // Optional customer email
            //'OWNERZIP' => $user['postal_code'], // Optional customer ZIP code
//            'OWNERADDRESS' => utf8_decode($user['address']), // Optional customer address
            //'OWNERTOWN' => null, // Optional customer city
            //'OWNERCTY' => $user['country'], // Optional customer country (Format AN)
//            'OWNERTELNO' => utf8_decode($user['phone_movil']), // Optional customer's phone number.
            //'PARAMPLUS' => 'userid=' . $user['userid'] // Params that are returned during the POSTSALE request after the payment at the PSP (ex: myparam1=123&myparam2=456)
        )));
        return new JsonResponse(['urlRedirect' => $url]);

    }

    /**
     * @Route("/phpFiles/psp/post_payment.php", name="postfinance_response")
     */
    public function postfinanceResponse(ReservationRepository $entityManager, \Swift_Mailer $mailer){

        // Définit l'environnement comme test (ne rien définir pour utiliser l'environnement de production)
//$ogone->env = 'test';

// Signature des paramètres
        $signature = $this->SignParamsOut($_GET);

// Debug
//echo "<p>Signature => " . $signature . "</p>"; echo '<p>GET => <pre>'; print_r($_GET); echo '</pre></p>'; exit;

// Erreur, les parametres on ete alteres
        if ( $_GET['STATUS'] == 9 )
        {

            // Get reservation and user data
            $reservation = $entityManager->find($_GET['orderID']);//$dataset->get_record_by_ID('reservation', 'reservation_number', $_GET['orderID']);
            $user = $reservation->getUser();
            $contract = $reservation->getContract();

            // Generate contract
//                $fileM = new TemplateProcessor();//new FileMannagament();
//                $contractPath = '../doc/contracts/user' . $user['userid'] . 'date' . $reservation['date'] . '/contract.docx';
//                $document = $fileM->loadTemplate($contractPath);
//                $document->setValue('pay_details', utf8_decode("Statut : " . $this->GetHumanStatus($_GET['STATUS'])));
//                $document->save($contractPath);

            // Send e-mail
            $message = (new \Swift_Message('Bienvenue chez Arena-Park'))
                ->setFrom('noreply@arena-park.ch')
                ->setTo($user->getEmail())
                ->addBcc('reservation@arena-park.ch')
                ->setBody(
                    $this->renderView(
                        'emails/reservation.html.twig',
                        array('user' => $user->getName())
                    ),
                    'text/html'
                )->attach(\Swift_Attachment::fromPath($contract->getPath()));

            $mailer->send($message);
            return $this->redirectToRoute('reservation', ['reservationConfirmed' => true]);
        }
        $message = (new \Swift_Message('Problema con la reservación'))
            ->setFrom('noreply@arena-park.ch')
            ->setTo('reservation@arena-park.ch')
            ->setBody(
                "Problema en el pago con postfinance de esta reservación, el contrato adjunto no se envió al cliente.",
                'text/html'
            )->attach(\Swift_Attachment::fromPath($contract->getPath()));
        $mailer->send($message);
        return $this->redirectToRoute('reservation', ['errorPayment' => 'Erreur durant la requete post sale.']);
//        if ( $signature != $_GET['SHASIGN'] )
//        {
//            return $this->redirectToRoute('reservation', ['errorPayment' => 'Erreur durant la requete post sale.']);
//        }
// Erreur durant la requete
//        else if ( $_GET['NCERROR'] != 0 )
//        {
//            echo "Erreur (NC " . $_GET['NCERROR'] . ") durant la requete post sale.";
//            exit;
//        }
// Aucune erreur durant la requete
//        else
//        {
//
//            else
//            {
//                echo "Erreur durant la requete post sale, statut de la transaction : " .  $_GET['STATUS'];
//                exit;
//            }
//        }
    }


    public function GetDefaultParams()
    {
        // RTFM at https://secure.ogone.com/ncol/param_cookbook.asp
        // Default Ogone params
        $ogone_params = array(
            'PSPID' => $this->pspid,
            'CURRENCY' => $this->currency_iso,
            'LANGUAGE' => $this->language_iso,
            'TP' => $this->template_url,
            'HOMEURL' => $this->home_url,
            'CATALOGURL' => $this->catalog_url,
            'ACCEPTURL' => $this->accept_url,
            'CN' => null, // Optional customer name
            'EMAIL' => null, // Optional customer email
            'OWNERZIP' => null, // Optional customer ZIP code
            'OWNERADDRESS' => null, // Optional customer address
            'OWNERTOWN' => null, // Optional customer city
            'OWNERCTY' => null, // Optional customer country
            'OWNERTELNO' => null, // Optional customer's phone number.

            // Combinaisons permettant de pré-sélectionner une méthode de paiement dans le formulaire d'Ogone
            'PM' => null, 'BRAND' => null,
            //'PM' => 'CreditCard', 'BRAND' => 'visa', // Visa
            //'PM' => 'CreditCard', 'BRAND' => 'eurocard', // MasterCard
            //'PM' => 'debit direct', 'BRAND' => '', // PostFinance card
            //'PM' => 'yellownet', 'BRAND' => '', // PostFinance e-finance

            //'WIN3DS' => 'MAINW', // Way to show the identification page to the customer (MAINW, POPUP, POPIX (DirectLink only))
            //'TXTOKEN' => 'INIT'
        );

        return $ogone_params;
    }


    public function EncodeParams($ogone_params)
    {
        // Uppercase all array keys
        $ogone_params = array_change_key_case($ogone_params, CASE_UPPER);

        // Sort params by key (alphabetically)
        ksort($ogone_params, SORT_STRING);

        $encoded_params = "";
        foreach ( $ogone_params as $key => $value )
        {
            if ( strlen($value) )
            {
                $encoded_params .= $key . '=' . rawurlencode($value) . '&';
            }
        }
        $encoded_params = preg_replace('/&$/', null, $encoded_params);

        return $encoded_params;
    }


    public function SignParamsIn($ogone_params)
    {
        // Uppercase all array keys
        $ogone_params = array_change_key_case($ogone_params, CASE_UPPER);

        // Sort params by key (alphabetically)
        ksort($ogone_params, SORT_STRING);

        $params_string = "";
        foreach ( $ogone_params as $key => $value )
        {
            if ( strlen($value) )
            {
                $params_string .= $key . '=' . $value . $this->in_secret;
            }
        }

        return strtoupper(hash('sha512', $params_string));
    }


    public function SignParamsOut($ogone_params)
    {
        // Define params to include (RTFM : https://secure.ogone.com/ncol/Ogone_e-Com-ADV_FR.pdf)
        $included = array(
            'AAVADDRESS',
            'AAVCHECK',
            'AAVZIP',
            'ACCEPTANCE',
            'ALIAS',
            'AMOUNT',
            'BIN',
            'BRAND',
            'CARDNO',
            'CCCTY',
            'CN',
            'COMPLUS',
            'CREATION_STATUS',
            'CURRENCY',
            'CVC',
            'CVCCHECK',
            'DCC_COMMPERCENTAGE',
            'DCC_CONVAMOUNT',
            'DCC_CONVCCY',
            'DCC_EXCHRATE',
            'DCC_EXCHRATESOURCE',
            'DCC_EXCHRATETS',
            'DCC_INDICATOR',
            'DCC_MARGINPERCENTAGE',
            'DCC_VALIDHOURS',
            'DIGESTCARDNO',
            'ECI',
            'ED',
            'ENCCARDNO',
            'IP',
            'IPCTY',
            'NBREMAILUSAGE',
            'NBRIPUSAGE',
            'NBRIPUSAGE_ALLTX',
            'NBRUSAGE',
            'NCERROR',
            'NCERRORCARDNO',
            'NCERRORCN',
            'NCERRORCVC',
            'NCERRORED',
            'ORDERID',
            'PAYID',
            'PM',
            'SCO_CATEGORY',
            'SCORING',
            'STATUS',
            'SUBBRAND',
            'SUBSCRIPTION_ID',
            'TRXDATE',
            'VC'
        );

        // Uppercase all array keys
        $ogone_params = array_change_key_case($ogone_params, CASE_UPPER);

        // Sort params by key (alphabetically)
        ksort($ogone_params, SORT_STRING);

        $params_string = "";
        foreach ( $ogone_params as $key => $value )
        {
            if ( in_array($key, $included) && strlen($value) )
            {
                $params_string .= $key . '=' . $value . $this->out_secret;
            }
        }

        return strtoupper(hash('sha512', $params_string));
    }


    public function GetInitRequestURL($ogone_params)
    {
        $ogone_params['SHASIGN'] = $this->SignParamsIn($ogone_params);

        // Redirect user to Ogone
        return 'https://' . $this->host . '/ncol/' . $this->env . '/orderstandard.asp?' . $this->EncodeParams($ogone_params);
    }


    public function GetInitPaymentURL($order_id, $amount, $custom_params = null)
    {
        if ( !is_array($custom_params) ) $custom_params = array();

        $ogone_params = $this->GetDefaultParams();

        $ogone_params['ORDERID'] = $order_id;

        // Total de la commande * 100
        $ogone_params['AMOUNT'] = $amount * 100;

        // Add custom params
        foreach ( $custom_params as $key => $value )
        {
            $ogone_params[$key] = $value;
        }

        return $this->GetInitRequestURL($ogone_params);
    }


    public function GetInitSubscriptionURL($order_id, $amount, $custom_params = null)
    {
        if ( !is_array($custom_params) ) $custom_params = array();

        $ogone_params = $this->GetDefaultParams();

        $ogone_params['ORDERID'] = $order_id;
        $ogone_params['SUB_ORDERID'] = $order_id;

        // Total de la commande * 100
        $ogone_params['AMOUNT'] = $amount * 100;
        $ogone_params['SUB_AMOUNT'] = $amount * 100;

        // Add custom params
        foreach ( $custom_params as $key => $value )
        {
            $ogone_params[$key] = $value;
        }

        return $this->GetInitRequestURL($ogone_params);
    }


    public function GetHumanStatus($status)
    {
        return self::$human_status[intval($status)];
    }
    /**
     * @Route("/post/finance", name="post_finance")
     */
    public function index()
    {
        return $this->render('post_finance/index.html.twig', [
            'controller_name' => 'PostFinanceController',
        ]);
    }
}
