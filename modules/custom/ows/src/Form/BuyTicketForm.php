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
        $form['card_name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Name (as it appears on your card'),
            '#attributes' => array(
                'class' => array('form-control')
            )
        );

        $form['card_number'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Card number'),
            '#attributes' => array(
                'class' => array('form-control')
            )
        );

        $form['exp_month'] = array(
            '#type' => 'select',
            '#title' => $this->t('Expiration'),
            '#options' => range(1, 12),
            '#attributes' => array(
                'class' => array('form-control')
            )
        );

        $form['exp_year'] = array(
            '#type' => 'select',
            '#title' => $this->t(''),
            '#options' => range(2016, 2030),
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
     
    }

    /*
    * form submit
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        /*kint($form_state);*/
    }

    // Change method name to avoid duplicate callback
    public function submitFormAjax(array &$form, FormStateInterface $form_state) {
        $response = new AjaxResponse();
        // -------------------------
        $path = drupal_get_path('module', 'ows');
        require_once($path.'/src/PayPal/autoload.php');

        /* Payal credit card payment */
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AbPf_36pBxcfrUKS_4k_aSGToBIC8B0iLV7CLKOvh2iVrUugMX90Mryz2dSZQwVjlud2SkIYB-CJMx6J',     // ClientID
                'EFGrn_1lFJdQNHApln8vnmo4M5ZIBQgbnVb8wUX2V2Smq1wPOKzzow5gbsIQBCAtXGUC5HnIZ0uRst-g'      // ClientSecret
            )
        );

        // ### CreditCard
        // A resource representing a credit card that can be
        // used to fund a payment.
        $card = new \PayPal\Api\CreditCard();
        $card->setType("visa")
            ->setNumber("4669424246660779")
            ->setExpireMonth("11")
            ->setExpireYear("2019")
            ->setCvv2("012")
            ->setFirstName("Joe")
            ->setLastName("Shopper");
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
        $item1->setName('Ground Coffee 40 oz')
            ->setDescription('Ground Coffee 40 oz')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setTax(0.3)
            ->setPrice(7.50);
        $itemList = new \PayPal\Api\ItemList();
        $itemList->setItems(array($item1));
        // ### Additional payment details
        // Use this optional field to set additional
        // payment information such as tax, shipping
        // charges etc.
        $details = new \PayPal\Api\Details();
        $details->setShipping(1.2)
            ->setTax(1.3)
            ->setSubtotal(17.5);
        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        $amount = new \PayPal\Api\Amount();
        $amount->setCurrency("USD")
            ->setTotal(20)
            ->setDetails($details);
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
        // For Sample Purposes Only.
        $request = clone $payment;
        // ### Create Payment
        // Create a payment by calling the payment->create() method
        // with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
        // The return object contains the state.
        try {
            $payment->create($apiContext);
        } catch (Exception $ex) {
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            \PayPal\Api\ResultPrinter::printError('Create Payment Using Credit Card. If 500 Exception, try creating a new Credit Card using <a href="https://ppmts.custhelp.com/app/answers/detail/a_id/750">Step 4, on this link</a>, and using it.', 'Payment', null, $request, $ex);
            exit(1);
        }
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        print_r($payment->getId());
        print_r($request);
        print_r($payment);

       

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
}
