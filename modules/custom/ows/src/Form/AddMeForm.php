<?php

/**
 * @file
 * Contains \Drupal\ows\Form\AddMeForm.
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
 * Class AddMeForm.
 *
 * @package Drupal\ows\Form
 */
class AddMeForm extends FormBase {

    var $user_empty;
    /**
    * {@inheritdoc}
    */
    public function getFormId() {
        return 'add_me_form';
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state) {
        //$form['#attached']['library'][] = 'core/drupal.dialog.ajax';
        $form['validator'] = array(
            '#markup' => '<div class="validate error"></div>
            <br>' // <div class="form-item">Please provide us with your full name and email address and we will add your name to our VIP list</div>
        );

        $form['name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Name'),
            '#attributes' => array(
                'class' => array('form-control')
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
            /*'#required' => true,*/
            '#attributes' => array(
                'class' => array('form-control')
            )
        );

        /*$form['captcha'] = array(
            '#markup' => '<div class="form-item"><div class="g-recaptcha" data-sitekey="6LeJJCUTAAAAAFMG5QlQHzoguSOI1kmMAjIsMiAL"></div></div>',
            '#attached' => array(
                'library' =>  array(      
                    'https://www.google.com/recaptcha/api.js', ''
                ),
            ),
        );*/

        $form['actions']['#type'] = 'actions';
            $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
            '#ajax' => array(
                'callback' => '::submitFormAjax',
            ),
        );

        $form['#title'] = 'Add Me';
        return $form;
    }

    /*
    * validate email field
    */
    public function validateMailCallback(array &$form, FormStateInterface $form_state) {
        /*// Instantiate an AjaxResponse Object to return.
        $response = new AjaxResponse();

        if (!$form_state->getValue('name')) {
            $response->addCommand(new HtmlCommand('.form-item-name .description', 'Please enter your full name!'));
        } else {
            $response->addCommand(new HtmlCommand('.form-item-name .description', ''));
        }

        if (!valid_email_address($form_state->getValue('mail'))) {
            $response->addCommand(new HtmlCommand('.form-item-mail .description', 'Invalid email adress!'));
        } else {
            $response->addCommand(new HtmlCommand('.form-item-mail .description', ''));
        }

        return $response;*/
    }

    // validate form
    public function validateForm(array &$form, FormStateInterface $form_state) {
     
    }

    /*
    * form submit
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
    }

    // Change method name to avoid duplicate callback
    public function submitFormAjax(array &$form, FormStateInterface $form_state) {
        $response = new AjaxResponse();

        $validate = false;
        $debug = false;
        $message = array();
        $values = $form_state->getValues();

        /*if (!$values['name']) {
            $message[] = 'Please enter your full name.';
        }*/

        // validate email
        if (!valid_email_address($values['mail'])) {
            $message[] = 'Invalid email adress.';
        }

        // validate flag
        if (count($message)) {
            $message = implode('<br>', $message);
            $response->addCommand(new HtmlCommand('.validate', $message));
            return $response;
        }

        // close dialog
        $response->addCommand(new CloseDialogCommand('.dialog-add-me'));
        // open message dialog
        $message = 'Your name has been added to our VIP list and you should receive information from us soon.';

        $fields = array('name' => $form_state->getValue('name'), 'mail' => $form_state->getValue('mail'), 'created' => time());
        db_insert('add_me')->fields($fields)->execute();

        // send email
        $module = 'ows';
        $key = 'add_me';
        $to = 'contact@officialworldssexiest.com';
        $params['title'] = 'OWS Add Me Submission';
        $params['message'] = 'Name:' . $form_state->getValue('name');
        $params['message'] .= '<br>Email:' . $form_state->getValue('mail');
        
        $mailManager = \Drupal::service('plugin.manager.mail');
        $langcode = \Drupal::currentUser()->getPreferredLangcode();
        $send = true;
        $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

        // ---------------------------
        // mail to sender
        $module = 'ows';
        $key = 'add_me_sender';
        $to = $form_state->getValue('mail');
        $params['title'] = 'Thank you for your submission';
        $params['message'] = 'Thank you for your submission to the Official World\'s Sexiest VIP List. We added your name to our VIP list. We will keep you updated about the progress of the contest and all the latest news. If you have any friends then please tell them about our great contest that will launch beginning of next year. If you consider yourself sexy, then you need to definitely enter the contest. After all, who will not want to be voted the sexiest in the world?

            Regards
            The Official World\'s Sexiest Team';
                
        $mailManager = \Drupal::service('plugin.manager.mail');
        $langcode = \Drupal::currentUser()->getPreferredLangcode();
        $send = true;
        $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

        if ($result['result'] !== true) {}

        // $message .= '<script>owsDialogCallback(1);</script>';
        $response->addCommand(new OpenModalDialogCommand('Thank you', $message), ['width' => '700']);
        return $response;
    }
}
