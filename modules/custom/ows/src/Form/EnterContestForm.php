<?php

/**
 * @file
 * Contains \Drupal\ows\Form\EnterContestForm.
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
 * Class EnterContestForm.
 *
 * @package Drupal\ows\Form
 */
class EnterContestForm extends FormBase {

    var $user_empty;
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

        $type = isset($_GET['type']) ? $_GET['type'] : "contestant";

        //$form['#attached']['library'][] = 'core/drupal.dialog.ajax';
        $form['validator'] = array(
            '#markup' => '<div class="validate error"></div>'
        );

        /*$form['tabs'] = array(
            '#markup' => '<div class="tabs">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#personal-information" data-toggle="tab">Personal Information</a></li>
                    <li><a href="#contact-information" data-toggle="tab">Contact Information</a></li>
                </ul>
            </div>
            <div class="clearfix"></div>'
        );*/

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

        $form['birthday'] = array(
            '#type' => 'date',
            '#title' => $this->t('Birthday'),
            '#date_year_range' => '-50:-10',
            '#attributes' => array(
                'class' => array('form-control')
            )
        );

        $user_entity = entity_load('user', 0);
        $countries = $user_entity->get('field_country')->getItemDefinition()->getFieldDefinition()->getSettings()['allowed_values'];
        $form['country'] = array(
            '#type' => 'select',
            '#title' => t('Country'),
            '#options' => $countries,
            '#attributes' => array(
                'class' => array('form-control')
            )
        );

        if ($type == "contestant") {
            $form['photo'] = array(
                '#type' => 'file',
                '#title' => $this->t('Photo'),
                '#attributes' => array(
                    //'class' => array('form-control')
                )
            );

            $form['voice'] = array(
                '#type' => 'file',
                '#title' => $this->t('Voice'),
                '#attributes' => array(
                    //'class' => array('form-control')
                )
            );
        }

        $form['gender'] = array(
            '#type' => 'radios',
            '#title' => $this->t('Gender'),
            '#options' => array('Male' => t('Male'), 'Female' => t('Female')),
            '#required' => true,
            '#attributes' => array(
                //'class' => array('form-control')
            )
        );

        if ($type == "contestant") {
            $form['eyes_color'] = array(
                '#type' => 'select',
                '#title' => $this->t('Eye Color'),
                '#options' => $this->getUserFieldValues('field_eyes_color'),
                '#required' => true,
                '#attributes' => array(
                    'class' => array('form-control')
                )
            );

            $form['hair_color'] = array(
                '#type' => 'select',
                '#title' => $this->t('Hair Color'),
                '#options' => $this->getUserFieldValues('field_hair_color'),
                '#attributes' => array(
                    'class' => array('form-control')
                )
            );

            $form['height'] = array(
                '#type' => 'number',
                '#title' => $this->t('Height'),
                '#attributes' => array(
                    'class' => array('form-control')
                )
            );

            $form['weight'] = array(
                '#type' => 'number',
                '#title' => $this->t('Weight'),
                '#attributes' => array(
                    'class' => array('form-control')
                )
            );

            $form['bust'] = array(
                '#type' => 'number',
                '#title' => $this->t('Bust'),
                '#attributes' => array(
                    'class' => array('form-control')
                )
            );

            $form['waist'] = array(
                '#type' => 'number',
                '#title' => $this->t('Waist'),
                '#attributes' => array(
                    'class' => array('form-control')
                )
            );

            $form['hips'] = array(
                '#type' => 'number',
                '#title' => $this->t('Hips'),
                '#attributes' => array(
                    'class' => array('form-control')
                )
            );
        }

        // $form['type'] = array('#type' => 'hidden', '#value' => $type);

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
    * get user field list values
    */
    public function getUserFieldValues($field) {
        $values = array();
        // create empty entity
        if (empty($user_empty)) {
            $this->user_empty = \Drupal::entityManager()->getStorage('user')->create(array());
            return $this->user_empty->getFieldDefinitions()[$field]->getItemDefinition()->getSettings()['allowed_values'];
        }
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

        $response->addCommand(new CloseDialogCommand('.dialog-enter-contest'));
        return $response;
    }

    // validate form
    public function validateForm(array &$form, FormStateInterface $form_state) {
     
    }

    /*
    * form submit
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // kint($form_state);
    }

    // Change method name to avoid duplicate callback
    public function submitFormAjax(array &$form, FormStateInterface $form_state) {
        $response = new AjaxResponse();

        $validate = false;
        $debug = false;
        $message = array();
        $values = $form_state->getValues();

        if ($debug) $message[] = print_r($form_state->getValues(), true);

        // validate email
        if (!valid_email_address($form_state->getValue('mail'))) {
            $message[] = 'Invalid email adress.';
        } else {
            if (user_load_by_mail($form_state->getValue('mail')) && $form_state->getValue('mail') != false) {
                $message[] = 'Email already exist.';
            }
        }

        // validate birthday
        $birthday = $values['birthday'];
        $year = date('Y', strtotime($birthday));
        $current_year = date('Y', time());
        if (($current_year - $year) < 18) {
            $message[] = 'You must be 18 to enter the contest.';    
        }

        $birthday = $values['birthday'];
        $year = date('Y-m-d', strtotime($birthday));

        // validate gender
        if (!$values['gender']) {
            $message[] = 'Gender is required.';
        }

        // validate flag
        if (count($message)) {
            $message = implode('<br>', $message);
            $response->addCommand(new HtmlCommand('.validate', $message));
            return $response;
        }

        // ----------------------------------
        // save user when all fields are fine
        $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $user = \Drupal\user\Entity\User::create();

        // Mandatory settings
        $user->setPassword('|contestant|');
        $user->enforceIsNew();
        $user->setEmail($form_state->getValue('mail'));
        $user->setUsername($form_state->getValue('mail'));

        // Optional settings
        /*
        $user->set("init", $form_state->getValue('mail'));
        $user->set("langcode", $language);
        $user->set("preferred_langcode", $language);
        $user->set("preferred_admin_langcode", $language);*/

        $type = $_GET['type']; //$form_state->get('type');
        // custom fields
        $user->set("field_gender", $form_state->get('gender'));
        $user->set("field_birthday", $birthday);
        $user->set("field_first_name", $form_state->get('first_name'));
        $user->set("field_last_name", $form_state->get('last_name'));
        $user->set("field_country", $form_state->get('country'));
        $user->set("field_age", date('Y', time()) - date('Y', strtotime($birthday)));

        // these fields are for contestant only
        if ($type != "voter") {
            $user->addRole('contestant');

            $user->set("field_bust", $form_state->get('bust'));
            $user->set("field_eyes_color", $form_state->get('eyes_color'));
            $user->set("field_waist", $form_state->get('waist'));
            $user->set("field_weight", $form_state->get('weight'));       
        
            // save photo
            $file = file_save_upload('photo');
            if ($file) {
                // set status permanent
                $file[0]->setPermanent();
                $file[0]->save();
                // move file from temporary:// to public://
                $file = file_move($file[0], 'public://'.$file[0]->getFilename());
                // set photo to user
                @$user->set('user_picture', array('target_id' => $file->id()));
            }

            $file = file_save_upload('voice');
            if ($file) {
                // set status permanent
                $file[0]->setPermanent();
                $file[0]->save();
                // move file from temporary:// to public://
                $file = file_move($file[0], 'public://'.$file[0]->getFilename());
                // set photo to user
                @$user->set('field_voice', array('target_id' => $file->id()));
            }
        } else {
            $user->addRole('voter');
        }

        // $user->activate();
        // save user
        $user->save();
        
        // No email verification required; log in user immediately.
        _user_mail_notify('register_no_approval_required', $user);
        //user_login_finalize($user);

        // drupal_set_message($this->t('Registration successful. You are now logged in.'));
        // $form_state->setRedirect('');

        
        
        // open message dialog
        $message = 'Please check your email to complete the registration.';
        // Append script into callback message
        $script = '<script>
            swal("Thank you", "We have sent you an email with activation link.", "success");
            jQuery(".sweet-alert").center();
        </script>';
        // $response->addCommand(new OpenModalDialogCommand('Thank you', $message), ['width' => '700', 'clkass' => 'dialog-thanks']);
        $response->addCommand(new HtmlCommand('.ui-dialog-title', $script));

        // -------------
        // close dialog
        if ($type != "voter") {
            $response->addCommand(new CloseDialogCommand('.dialog-enter-contest'));
        } else {
            $response->addCommand(new CloseDialogCommand('.dialog-vote'));
        }

        return $response;
    }

    /*// return ajax dialog
    public function open_modal(&$form, FormStateInterface $form_state) {
        $response = new AjaxResponse();
        if ($id !== NULL) {
            $content = 'Test';
            $options = array(
                'dialogClass' => 'popup-dialog-class',
                'width' => '300',
                'height' => '300',
            );

            $response->addCommand(new OpenModalDialogCommand($title, $content, $options));
        } else {
            $content = 'Not found record with this title';
            $response->addCommand(new OpenModalDialogCommand('Test', $content));
        }

        return $response;
    }*/
}
