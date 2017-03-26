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

        $form['pass'] = array(
            '#type' => 'password',
            '#title' => $this->t('Password'),
            '#attributes' => array(
                'class' => array('form-control')
            )
        );

        $form['pass_confirm'] = array(
            '#type' => 'password',
            '#title' => $this->t('Confirm Password'),
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
            $form['photo_id'] = array(
                '#type' => 'file',
                '#title' => $this->t('Photo ID'),
                '#attributes' => array(
                    //'class' => array('form-control')
                )
            );

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

            $form['measurements'] = array(
                '#type' => 'select',
                '#title' => $this->t('Measurement'),
                '#options' => $this->getUserFieldValues('field_measurements'),
                // array('US Standard' => 'US Standard', 'Metric' => 'Metric'), // Metric: cm, kg. US: Pound, inch
                '#required' => true,
                '#attributes' => array(
                    'class' => array('form-control')
                )
            );
            
            $form['height'] = array(
                '#type' => 'number',
                '#title' => $this->t('Height'),
                '#attributes' => array(
                    'class' => array('form-control')
                ),
                '#description' => '<div class="measurement-suffix"></div>'
            );

            $form['weight'] = array(
                '#type' => 'number',
                '#title' => $this->t('Weight'),
                '#attributes' => array(
                    'class' => array('form-control')
                ),
                '#description' => '<div class="measurement-suffix"></div>'
            );

            $form['bust'] = array(
                '#type' => 'number',
                '#title' => $this->t('Bust'),
                '#attributes' => array(
                    'class' => array('form-control')
                ),
                '#description' => '<div class="measurement-suffix"></div>'
            );

            $form['waist'] = array(
                '#type' => 'number',
                '#title' => $this->t('Waist'),
                '#attributes' => array(
                    'class' => array('form-control')
                ),
                '#description' => '<div class="measurement-suffix"></div>'
            );

            $form['hips'] = array(
                '#type' => 'number',
                '#title' => $this->t('Hips'),
                '#attributes' => array(
                    'class' => array('form-control')
                ),
                '#description' => '<div class="measurement-suffix"></div>'
            );
        }

        // $form['type'] = array('#type' => 'hidden', '#value' => $type);

        $form['recaptcha'] = array(
            '#markup' => '<div id="g-recaptcha" class="g-recaptcha" data-sitekey="6LeJJCUTAAAAAFMG5QlQHzoguSOI1kmMAjIsMiAL"></div>'
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
            $response->addCommand(new HtmlCommand('.form-item-mail .description', 'Invalid email address!'));
        } else {
            // validate email
            if (user_load_by_mail($form_state->getValue('mail')) && $form_state->getValue('mail') != false) {
                $response->addCommand(new HtmlCommand('.form-item-mail .description', 'Email already exist!'));
                //$response->addCommand(new InvokeCommand('#edit-mail', 'css', array('color', '#ff0000')));
            } else {
                $response->addCommand(new HtmlCommand('.form-item-mail .description', ''));
            }
        }

        // validate password
        $pass = $form_state->getValue('pass');
        $pass_confirm = $form_state->getValue('pass_confirm');
        if ($pass != $pass_confirm) {
            $message[] = 'Password must matched!';
        }

        // $response->addCommand(new CloseDialogCommand('.dialog-enter-contest'));
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
        $path = drupal_get_path('module', 'ows');
        require_once($path.'/src/recaptchalib.php');

        // validate captcha
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $captcha_response = $_POST['g-recaptcha-response'];
        $params = array('secret'=> $secret, 'response'=> $captcha_response);
        $json = json_decode(file_get_contents($url.'?secret='.GOOGLE_RECAPTCHA_SECRET_KEY.'&response='.$captcha_response));       

        // --------------------------
        $response = new AjaxResponse();

        $validate = false;
        $debug = false;
        $message = array();
        $values = $form_state->getValues();

        if ($debug) $message[] = print_r($form_state->getValues(), true);

        // validate email
        if (!valid_email_address($form_state->getValue('mail'))) {
            $message[] = 'Invalid email address.';
        } else {
            if (user_load_by_mail($form_state->getValue('mail')) && $form_state->getValue('mail') != false) {
                $message[] = 'Email already exist.';
            }
        }

        // validate password
        $pass = $form_state->getValue('pass');
        $pass_confirm = $form_state->getValue('pass_confirm');
        if ($pass != $pass_confirm) {
            $message[] = 'Password must matched.';
        }

        // for dev
        if ($validate) {
            // validate first and last name
            $first_name = $form_state->getValue('first_name');
            $last_name = $form_state->getValue('last_name');
            if (!$first_name) {
                $message[] = 'First name is required.';
            }

            if (!$last_name) {
                $message[] = 'Last name is required.';
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

            // validate files
            $photo_id = file_save_upload('photo_id');
            if (!$photo_id) {
                $message[] = 'Photo ID is required.';
            }

            $photo = file_save_upload('photo');
            if (!$photo_id) {
                $message[] = 'Photo is required.';
            }

            $voice = file_save_upload('voice');
            if (!$voice) {
                $message[] = 'Voice file is required.';
            }

            // validate gender
            if (!$values['gender']) {
                $message[] = 'Gender is required.';
            }

            // eye color
            if (!$values['eyes_color']) {
                $message[] = 'Eye color is required.';
            }

            // hair color
            if (!$values['hair_color']) {
                $message[] = 'Hair color is required.';
            }

            // height
            if (!$values['weight']) {
                $message[] = 'Weight is required.';
            }

            // bust
            if (!$values['bust']) {
                if ($values['gender'] == "Male") {
                    $message[] = 'Chest is required.';
                } else {
                    $message[] = 'Bust is required.';
                }
            }

            // waist
            if (!$values['waist']) {
                $message[] = 'Waist is required.';
            }

            // hips
            if (!$values['hips']) {
                $message[] = 'Hips is required.';
            }
        }

        // validate flag
        if (count($message)) {
            $message = implode('<br>', $message);

            // scrollto error message
            $message .= '<script>
                jQuery(".dialog-edit-my-account .ui-dialog-content").mCustomScrollbar("scrollTo", jQuery(".validate.error"));
            </script>';
            $response->addCommand(new HtmlCommand('.validate', $message));

            $response->addCommand(new HtmlCommand('.validate', $message));
            return $response;
        }

        if (!$json->success) {
            /*$response->addCommand(new HtmlCommand('.validate', "Please verify you're human by click on \"I'm not a robot\""));
            return $response;*/
        }

        // ----------------------------------
        // save user when all fields are fine
        $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $user = \Drupal\user\Entity\User::create();

        // Mandatory settings
        $user->setPassword($form_state->getValue('pass'));
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
        $user->set("field_gender", $form_state->getValue('gender'));
        $user->set("field_birthday", $birthday);
        $user->set("field_first_name", $form_state->getValue('first_name'));
        $user->set("field_last_name", $form_state->getValue('last_name'));
        $user->set("field_country", $form_state->getValue('country'));
        //$user->set("field_age", date('Y', time()) - date('Y', strtotime($birthday)));

        // these fields are for contestant only
        if ($type != "voter") {
            $user->addRole('contestant');

            $user->set("field_bust", $form_state->getValue('bust'));
            $user->set("field_eyes_color", $form_state->getValue('eyes_color'));
            $user->set("field_hair_color", $form_state->getValue('hair_color'));
            $user->set("field_waist", $form_state->getValue('waist'));
            $user->set("field_weight", $form_state->getValue('weight'));
            $user->set("field_hip", $form_state->getValue('hips'));
        
            // save photo id
            $photo_id = file_save_upload('photo_id');
            if ($photo_id) {
                // set status permanent
                $photo_id[0]->setPermanent();
                $photo_id[0]->save();
                // move file from temporary:// to public://
                $photo_id = file_move($photo_id[0], 'public://'.$photo_id[0]->getFilename());
                // set photo to user
                @$user->set('field_photo_id', array('target_id' => $photo_id->id()));
            }

            // save photo
            
            if ($photo) {
                // set status permanent
                $photo[0]->setPermanent();
                $photo[0]->save();
                // move file from temporary:// to public://
                $photo = file_move($photo[0], 'public://'.$photo[0]->getFilename());
                // set photo to user
                @$user->set('user_picture', array('target_id' => $photo->id()));
            }

            
            if ($voice) {
                // set status permanent
                $voice[0]->setPermanent();
                $voice[0]->save();
                // move file from temporary:// to public://
                $voice = file_move($voice[0], 'public://'.$voice[0]->getFilename());
                // set photo to user
                @$user->set('field_voice', array('target_id' => $voice->id()));
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
            swal({
                title: "Thank you",
                text: "We have sent you an email with activation link.",
                type: "success",
                customClass: "registration-completed"
            });

            // bind click event to the button OK
            jQuery(".registration-completed").click(function() {
                // show login
                jQuery("#block-mainmenu .login a").trigger("click");
            });

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
