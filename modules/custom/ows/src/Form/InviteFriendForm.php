<?php

/**
 * @file
 * Contains \Drupal\ows\Form\InviteFriendForm.
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
 * Class InviteFriendForm.
 *
 * @package Drupal\ows\Form
 */
class InviteFriendForm extends FormBase {

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
            '#markup' => '<div class="validate error"></div>'
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

        $form['actions']['#type'] = 'actions';
            $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Register'),
            '#ajax' => array(
                'callback' => '::submitFormAjax',
            ),
        );

        $form['#title'] = 'Enter the Contest';
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
        } else {
            // validate email
            if (user_load_by_mail($form_state->getValue('mail')) && $form_state->getValue('mail') != false) {
                $response->addCommand(new HtmlCommand('.form-item-mail .description', 'Email already exist!'));
                //$response->addCommand(new InvokeCommand('#edit-mail', 'css', array('color', '#ff0000')));
            } else {
                $response->addCommand(new HtmlCommand('.form-item-mail .description', ''));
            }
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
        kint($form_state);
    }

    // Change method name to avoid duplicate callback
    public function submitFormAjax(array &$form, FormStateInterface $form_state) {
        $response = new AjaxResponse();

        $validate = false;
        $debug = false;
        $message = array();
        $values = $form_state->getValues();

        // close dialog
        $response->addCommand(new CloseDialogCommand('.dialog-enter-contest'));
        // open message dialog
        $message = 'Please check your email to complete the registration.';
        $message .= '<script>owsDialogCallback(1);</script>';
        $response->addCommand(new OpenModalDialogCommand('Thank you', $message), ['width' => '700']);
        return $response;
    }
}
