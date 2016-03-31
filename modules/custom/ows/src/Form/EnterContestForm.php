<?php

/**
 * @file
 * Contains \Drupal\ows\Form\EnterContestForm.
 */

namespace Drupal\ows\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Class EnterContestForm.
 *
 * @package Drupal\ows\Form
 */
class EnterContestForm extends FormBase {

    /**
    * {@inheritdoc}
    */
    public function getFormId() {
        return 'enter_contest_form';
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
        
        /*$entity = \Drupal::entityManager()->getStorage('user')->create(array());
        $formObject = \Drupal::entityManager()->getFormObject('user', 'register')->setEntity($entity);
        $form = \Drupal::formBuilder()->getForm($formObject);
        */

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
                    'message' => NULL,
                ),
            ),
            '#description' => ' '
        );

        $form['first_name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('First Name'),
        );

        $form['last_name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Last Name'),
        );

        $form['birthday'] = array(
            '#type' => 'date',
            '#title' => $this->t('Birthday'),
        );

        $form['gender'] = array(
            '#type' => 'radios',
            '#title' => $this->t('Gender'),
            '#options' => array('Male' => t('Male'), 'Female' => t('Female')),
        );

        $form['photo'] = array(
            '#type' => 'managed_file',
            '#title' => $this->t('Photo'),
        );

        $form['voice'] = array(
            '#type' => 'managed_file',
            '#title' => $this->t('Voice'),
        );



        $form['actions']['#type'] = 'actions';
            $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Load'),
            '#ajax' => array(
                'callback' => '::submitForm',
            ),
        );

        $form['#title'] = 'Load node ID';
        return $form;
    }

    public function validateMailCallback(array &$form, FormStateInterface $form_state) {
        // Instantiate an AjaxResponse Object to return.
        $response = new AjaxResponse();

        if (!valid_email_address($form_state->getValue('mail'))) {
            $response->addCommand(new HtmlCommand('.form-item-mail .description', 'Invalid email adress!'));
        }

        // validate email
        if (user_load_by_mail($form_state->getValue('mail')) && $form_state->getValue('mail') != false) {
            $response->addCommand(new HtmlCommand('.form-item-mail .description', 'Email already exist!'));
            //$response->addCommand(new InvokeCommand('#edit-mail', 'css', array('color', '#ff0000')));
        }

        return $response;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {/*
        $response = new AjaxResponse();
             
        $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $user = \Drupal\user\Entity\User::create();

        // Mandatory settings
        $user->setPassword('|contestant|');
        $user->enforceIsNew();
        $user->setEmail($form_state->getValue('mail'));
        $user->setUsername($form_state->getValue('mail'));

        // Optional settings
        $user->set("init", $form_state->getValue('mail'));
        $user->set("langcode", $language);
        $user->set("preferred_langcode", $language);
        $user->set("preferred_admin_langcode", $language);
        $user->set("field_gender", "Male");
        // $user->activate();

        //Save user
        // $res = $user->save();
        // kint($user);

        // No email verification required; log in user immediately.
        //_user_mail_notify('register_no_approval_required', $user);
        user_login_finalize($user);

        // drupal_set_message($this->t('Registration successful. You are now logged in.'));
        // $form_state->setRedirect('');

        $response->addCommand(new OpenModalDialogCommand('Thank you', 'You have entered the contest!'));
        return $response;
        */
    }

    /*
    public function open_modal(&$form, FormStateInterface $form_state) {
        $node_title = $form_state->getValue('node_title');
        $query = \Drupal::entityQuery('node')->condition('title', $node_title);
        $entity = $query->execute();

        $title = 'Node ID';
        $key = array_keys($entity);
        $id = !empty($key[0]) ? $key[0] : NULL;
        $response = new AjaxResponse();
        if ($id !== NULL) {
            $content = '<div class="test-popup-content"> Node ID is: ' . $id . '</div>';
            $options = array(
            'dialogClass' => 'popup-dialog-class',
            'width' => '300',
            'height' => '300',
            );
            $response->addCommand(new OpenModalDialogCommand($title, $content, $options));
        } else {
            $content = 'Not found record with this title <strong>' . $node_title .'</strong>';
            $response->addCommand(new OpenModalDialogCommand($title, $content));
        }

        return $response;
    }
    */
}
