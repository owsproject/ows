<?php

/**
 * @file
 * Contains \Drupal\ows\Form\BuyTicketForm.
 */

namespace Drupal\ows\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\CloseDialogCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Class BuyTicketForm.
 *
 * @package Drupal\ows\Form
 */
class BuyTicketForm extends FormBase {

    var $user_empty;
    /**
    * {@inheritdoc}
    */
    public function getFormId() {
        return 'invite_friend_form';
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state) {
        //$form['#attached']['library'][] = 'core/drupal.dialog.ajax';

        $form['validator'] = array(
            '#markup' => '<div class="">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua.</div>
            <div class="validate error"></div>'
        );

        $form['gender'] = array(
            '#type' => 'select',
            '#title' => $this->t('Gender'),
            '#options' => array('Male' => t('Male'), 'Female' => t('Female')),
            '#required' => true,
            '#attributes' => array(
                //'class' => array('form-control')
            )
        );

        $form['mail'] = array(
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#ajax' => array(
                // Function to call when event on form element triggered.
                'callback' => array($this, 'validateMailCallback'),
                // Effect when replacing content. Options: 'none' (default), 'slide', 'fade'.
                'effect' => 'fade',
                // Javascript event to trigger Ajax. Currently for: 'onchange'.
                'event' => 'change',
                'progress' => array(
                    // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
                    'type' => 'throbber',
                    // Message to show along progress graphic. Default: 'Please wait...'.
                    'message' => 'Verifying email...',
                ),
            ),
            '#description' => ' ',
            '#required' => true,
            '#attributes' => array(
                'class' => array('form-control')
            )
        );

        $form['first_name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('First Name'),
            '#attributes' => array(
                'class' => array('form-control')
            )
        );

        $form['last_name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Last Name'),
            '#attributes' => array(
                'class' => array('form-control')
            )
        );

        $form['quantity'] = array(
            '#type' => 'select',
            '#title' => $this->t('Number of ticket'),
            '#options' => range(1, 100),
            '#required' => true,
            '#default_value' => 1,
            '#attributes' => array(
                //'class' => array('form-control')
            )
        );

        // credit card form
        $form['card_first_name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Card first name'),
            '#attributes' => array(
                'class' => array('form-control')
            ),
            '#description' => '(as it appears on your card)'
        );

        $form['card_last_name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Card last name'),
            '#attributes' => array(
                'class' => array('form-control')
            ),
            '#description' => '(as it appears on your card)'
        );

        $form['card_number'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Card number'),
            '#attributes' => array(
                'class' => array('form-control')
            )
        );

        $months = range(1, 12);
        $month_options = array();
        foreach ($months as $y) {
            $month_options[$y] = $y;
        }

        $form['exp_month'] = array(
            '#type' => 'select',
            '#title' => $this->t('Expiration'),
            '#options' => $month_options,
            '#attributes' => array(
                'class' => array('form-control')
            )
        );

        $years = range (date("Y"), date("Y") + 5);
        $year_options = array();
        foreach ($years as $y) {
            $year_options[$y] = $y;
        }

        $form['exp_year'] = array(
            '#type' => 'select',
            '#title' => $this->t(''),
            '#options' => $year_options,
            '#attributes' => array(
                'class' => array('form-control')
            )
        );

        $form['card_cvv'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Security code'),
            '#attributes' => array(
                'class' => array('form-control')
            )
        );

        $form['actions']['#type'] = 'actions';
            $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Buy'),
            '#ajax' => array(
                'callback' => '::submitFormAjax',
            ),
        );

        $form['#title'] = 'Buy Ticket';

        // test paypal
        

        return $form;
    }

    /*
    * validate email field
    */
    public function validateMailCallback(array &$form, FormStateInterface $form_state) {
        // Instantiate an AjaxResponse Object to return.
        $response = new AjaxResponse();

        if (!valid_email_address($form_state->getValue('mail'))) {
            $response->addCommand(new HtmlCommand('.form-item-mail .description', 'Invalid email adress!'));
        }

        return $response;
    }

    // validate form
    public function validateForm(array &$form, FormStateInterface $form_state) {
        $path = drupal_get_path('module', 'ows');
        require_once($path.'/src/common.inc');

        $response = new AjaxResponse();

        // Check credit card number
        $card_number = $form_state->getValue('card_number');
        $exp_month = $form_state->getValue('exp_month');
        $exp_year = $form_state->getValue('exp_year');
        $ccv = $form_state->getValue('card_ccv');

        if (!$card_number || !ValidCreditcard($card_number)) {
            $response->addCommand(new HtmlCommand('.validate', 'Invalid credit card number.'));
        }

        if(strtotime(substr(date('Y'), 0, 2)."{$exp_year}-{$exp_month}" ) < strtotime( date("Y-m"))) {
            $response->addCommand(new HtmlCommand('.validate', 'Your card is expired.'));
        }

        return $response;
    }

    /*
    * form submit
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        /*kint($form_state);*/
    }

    // Change method name to avoid duplicate callback
    public function submitFormAjax(array &$form, FormStateInterface $form_state) {
        $path = drupal_get_path('module', 'ows');
        require_once($path.'/src/PayPal/autoload.php');

        // -------------------------
        $response = new AjaxResponse();

        $clientId = 'AbPf_36pBxcfrUKS_4k_aSGToBIC8B0iLV7CLKOvh2iVrUugMX90Mryz2dSZQwVjlud2SkIYB-CJMx6J';
        $clientSecret = 'EFGrn_1lFJdQNHApln8vnmo4M5ZIBQgbnVb8wUX2V2Smq1wPOKzzow5gbsIQBCAtXGUC5HnIZ0uRst-g';

        /* Payal credit card payment */
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $clientId,
                $clientSecret
            )
        );

        $apiContext->setConfig(
            array(
                'mode' => 'sandbox',
                'log.LogEnabled' => true,
                'log.FileName' => '../PayPal.log',
                'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
                'cache.enabled' => true,
                // 'http.CURLOPT_CONNECTTIMEOUT' => 30
                // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
                //'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
            )
        );

        // ### CreditCard
        // A resource representing a credit card that can be
        // used to fund a payment.
        $card_number = $form_state->getValue('card_number');
        $exp_month = $form_state->getValue('exp_month');
        $exp_year = $form_state->getValue('exp_year');
        $ccv = $form_state->getValue('card_ccv');

        $card_first_name = $form_state->getValue('card_first_name');
        $card_last_name = $form_state->getValue('card_last_name');

        $total_amount = $form_state->getValue('quantity') * 5;

        // ### CreditCard
        // A resource representing a credit card that can be
        // used to fund a payment.
        $card = new \PayPal\Api\CreditCard();
        $card->setType(strtolower($this->cardType($card_number)))
            ->setNumber($card_number)
            ->setExpireMonth($exp_month)
            ->setExpireYear($exp_year)
            ->setCvv2($ccv)
            ->setFirstName($card_first_name)
            ->setLastName($card_last_name);
        // ### FundingInstrument
        // A resource representing a Payer's funding instrument.
        // For direct credit card payments, set the CreditCard
        // field on this object.
        $fi = new \PayPal\Api\FundingInstrument();
        $fi->setCreditCard($card);
        // ### Payer
        // A resource representing a Payer that funds a payment
        // For direct credit card payments, set payment method
        // to 'credit_card' and add an array of funding instruments.
        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod("credit_card")
            ->setFundingInstruments(array($fi));
        // ### Itemized information
        // (Optional) Lets you specify item wise
        // information
        $item1 = new \PayPal\Api\Item();
        $item1->setName('Official Sexiest Contest ticket x'.$quantity)
            ->setDescription('Official Sexiest Contest ticket x'.$quantity)
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setTax(0)
            ->setPrice($total_amount);

        $itemList = new \PayPal\Api\ItemList();
        $itemList->setItems(array($item1));
        // ### Additional payment details
        // Use this optional field to set additional
        // payment information such as tax, shipping
        // charges etc.
        $details = new \PayPal\Api\Details();
        $details->setShipping(0)
            ->setTax(0)
            ->setSubtotal(0);
        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        $amount = new \PayPal\Api\Amount();
        $amount->setCurrency("USD")
            ->setTotal($total_amount);
            //->setDetails($details);
        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. 
        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());


        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to sale 'sale'
        $payment = new \PayPal\Api\Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions(array($transaction));

        try {
            // ### Create Payment
            // Create a payment by calling the payment->create() method
            // with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
            // The return object contains the state.
            $payment->create($apiContext);
            if ($payment->getId() && $payment->state) {
                $user = \Drupal::currentUser();
                $fields = array(
                    'mail' => $form_state->getValue('mail'), 
                    'uid' => $user->id(), 
                    'gender' => $form_state->getValue('gender'), 
                    'quantity' => $form_state->getValue('quantity'), 
                    'amount' => $total_amount, 
                    'created' => time()
                );

                db_insert('win_a_date')->fields($fields)->execute();

                // payment created.
                $response->addCommand(new CloseDialogCommand('.dialog-buy-ticket'));
                // open message dialog
                $message = 'Thank you, your payment has been made.';
                $message .= '<script>owsDialogCallback(1);</script>';
                print 1;
                $response->addCommand(new OpenModalDialogCommand('Thank you', $message), ['width' => '700']);
            } else {
                $response->addCommand(new OpenModalDialogCommand('Error!', "Payment failed, please try again."), ['width' => '700']);
            }
        } catch (Exception $e) {
            print_r($e);
            // exit(1);
        }     

        /*$validate = false;
        $debug = false;
        $message = array();
        $values = $form_state->getValues();

        // close dialog
        $response->addCommand(new CloseDialogCommand('.dialog-buy-ticket'));
        // open message dialog
        $message = 'Please check your email to complete the registration.';
        $message .= '<script>owsDialogCallback(1);</script>';
        $response->addCommand(new OpenModalDialogCommand('Thank you', $message), ['width' => '700']);
        */
        return $response;
    }

    public function cardType($number) {
        $number=preg_replace('/[^\d]/','',$number);
        if (preg_match('/^3[47][0-9]{13}$/',$number))
        {
            return 'American Express';
        }
        elseif (preg_match('/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',$number))
        {
            return 'Diners Club';
        }
        elseif (preg_match('/^6(?:011|5[0-9][0-9])[0-9]{12}$/',$number))
        {
            return 'Discover';
        }
        elseif (preg_match('/^(?:2131|1800|35\d{3})\d{11}$/',$number))
        {
            return 'JCB';
        }
        elseif (preg_match('/^5[1-5][0-9]{14}$/',$number))
        {
            return 'MasterCard';
        }
        elseif (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/',$number))
        {
            return 'Visa';
        }
        else
        {
            return 'Unknown';
        }
    }
}
