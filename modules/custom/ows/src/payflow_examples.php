<?php
  /**
  * Exaples PeyFlow Pro - PHP - Curl
  * API rebuild by Radu Manole, radu@u-zine.com, 03-2007
  */

require('payflow_curl.php');

$payflow = new payflow($_POST['VENDOR'], $_POST['USER'], $_POST['PARTNER'], $_POST['PWD']);

if ($payflow->get_errors()) {
    echo $payflow->get_errors();
    exit;
} 

if ($_POST['TRXTYPE'] == 'S') {

    /**
    * Sale Transactions
    * params: card number, card expire date, amount, currency (default USD), extra parameters
    * return: success: response array, fail: false - error message
    */  

    // extra params 
    $data_array = array(
        'comment1' => 'Sale order',
        'firstname' => 'John',
        'lastname' => 'Doe',
        'street' => 'Oak Streen 123',
        'city' => 'San Francisco',
        'state' => 'CA',
        'zip' => '210202',
        'country' => 'US', // iso codes
        'cvv' => '123', // for cvv validation response
        'clientip' => '0.0.0.0'
    );

    $result = $payflow->sale_transaction($_POST['ACCT'], $_POST['EXPDATE'], $_POST['AMT'], $_POST['currency'], $data_array);

    if (!$payflow->get_errors()) {
        var_dump($result); // save in local app
    } else {  
        echo $payflow->get_errors();
    }

} elseif ($_POST['TRXTYPE'] == 'A') {
    /**
    * Authorization
    * params: card number, card expire date, amount, card holder name, currency (default USD)
    * return: success: response array, fail: false - error message
    */  

    $result = $payflow->authorization($_POST['ACCT'], $_POST['EXPDATE'], $_POST['AMT'], 'john doe', $_POST['currency']);
    if (!$payflow->get_errors()) {
        var_dump($result); // save in local app
    } else {  
        echo $payflow->get_errors();
    }
} elseif ($_POST['TRXTYPE'] == 'D') {

    /**
    * Delayed Capture
    * params: origid
    * return: success: response array, fail: false - error message
    */  

    $result = $payflow->delayed_capture($_POST['ORIGID']);
    if (!$payflow->get_errors()) {
        var_dump($result); // save in local app
    } else {  
        echo $payflow->get_errors();
    }
} elseif ($_POST['TRXTYPE'] == 'AD') {

    /**
    * Authorization followed by Delay Capture
    * params: card number, card expire date, amount, card holder name, currency (default USD)
    * return: success: response array, fail: false - error message
    */  

    $result = $payflow->authorization_delayed_capture($_POST['ACCT'], $_POST['EXPDATE'], $_POST['AMT'], 'john doe', $_POST['currency']);
    if (!$payflow->get_errors()) {
        var_dump($result); // save in local app
    } else {  
        echo $payflow->get_errors();
    }

} elseif ($_POST['TRXTYPE'] == 'C') {

    /**
    * Credit
    * params: origid
    * return: success: response array, fail: false - error message
    */  

    $result = $payflow->credit_transaction($_POST['ORIGID']);
    if (!$payflow->get_errors()) {
        var_dump($result); // save in local app
    } else {  
        echo $payflow->get_errors();
    }

} elseif ($_POST['TRXTYPE'] == 'V') {

    /**
    * Void
    * params: origid
    * return: success: response array, fail: false - error message
    */  

    $result = $payflow->void_transaction($_POST['ORIGID']);
    if (!$payflow->get_errors()) {
    var_dump($result); // save in local app
    } else {  
    echo $payflow->get_errors();
    }
}
