<?php

/**
 * @file
 * Contains \Drupal\ows\Form\ProfileForm.
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
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;

use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Class EnterContestForm.
 *
 * @package Drupal\ows\Form
 */
class ProfileForm extends FormBase {

    var $user_empty;
    /**
    * {@inheritdoc}
    */
    public function getFormId() {
        return 'profile_form';
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $user = \Drupal::currentUser();
        $user = user_load($user->id());

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
            ),
            '#default_value' => $user->get('mail')->value
        );

        $form['first_name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('First Name'),
            '#attributes' => array(
                'class' => array('form-control')
            ),
            '#default_value' => $user->get('field_first_name')->value
        );

        $form['last_name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Last Name'),
            '#attributes' => array(
                'class' => array('form-control')
            ),
            '#default_value' => $user->get('field_last_name')->value
        );

        $form['birthday'] = array(
            '#type' => 'date',
            '#title' => $this->t('Birthday'),
            '#date_year_range' => '-50:-10',
            '#attributes' => array(
                'class' => array('form-control')
            ),
            '#default_value' => $user->get('field_birthday')->value
        );

        $user_entity = entity_load('user', 0);
        $countries = $user_entity->get('field_country')->getItemDefinition()->getFieldDefinition()->getSettings()['allowed_values'];
        $form['country'] = array(
            '#type' => 'select',
            '#title' => t('Country'),
            '#options' => $countries,
            '#attributes' => array(
                'class' => array('form-control')
            ),
            '#default_value' => $user->get('field_country')->value
        );

        if ($type == "contestant") {
            $photo_uploaded = '';
            if (!empty($user->user_picture->target_id)) {
                $file = File::load($user->user_picture->target_id);
                $file_url = file_create_url(file_url_transform_relative($file->getfileUri()));
                $image_style_id = 'thumbnail';
                $style = ImageStyle::load($image_style_id);
                $image_url = file_url_transform_relative($style->buildUrl($file->getfileUri()));
                $photo_uploaded .= '<div class="photo-image"><a href="'.$file_url.'" target="_blank"><img src="'.$image_url.'" /></a></div>';
            }

            $form['photo'] = array(
                '#type' => 'file',
                '#title' => $this->t('Photo'),
                '#attributes' => array(
                    //'class' => array('form-control')
                ),
                '#prefix' => '<div class="form-item-photo">',
                '#suffix' => $photo_uploaded . '</div>'
            );

            $photo_id_uploaded = '';
            if (!empty($user->get('field_photo_id')->get(0))) {
                $file_id = $user->get('field_photo_id')->get(0)->getValue()['target_id'];
                $file = File::load($file_id);
                $file_url = file_create_url(file_url_transform_relative($file->getfileUri()));
                $image_style_id = 'thumbnail'; //$this->config('core.entity_view_display.user.user.compact')->get('content.user_picture.settings.image_style');
                $style = ImageStyle::load($image_style_id);
                $image_url = file_url_transform_relative($style->buildUrl($file->getfileUri()));
                $photo_id_uploaded .= '<div class="photo-image"><a href="'.$file_url.'" target="_blank"><img src="'.$image_url.'" /></a></div>';
            }

            $form['photo_id'] = array(
                '#type' => 'file',
                '#title' => $this->t('Photo ID'),
                '#attributes' => array(
                    //'class' => array('form-control')
                ),
                '#prefix' => '<div class="form-item-photo-id">',
                '#suffix' => $photo_id_uploaded . '</div>'
            );

            $voice_uploaded = '';
            if (!empty($user->get('field_voice')->get(0))) {
                $file_id = $user->get('field_voice')->get(0)->getValue()['target_id'];
                $file = File::load($file_id);
                $file_url = file_create_url(file_url_transform_relative($file->getfileUri()));
                $voice_uploaded .= '<div class="voice-file"><a href="'.$file_url.'" target="_blank">'.$file->getFilename().'</a></div>';
            }

            $form['voice'] = array(
                '#type' => 'file',
                '#title' => $this->t('Voice'),
                '#attributes' => array(
                    //'class' => array('form-control')
                ),
                '#prefix' => '<div class="form-item-voice">',
                '#suffix' => $voice_uploaded . '</div>'
            );
        }

        $form['gender'] = array(
            '#type' => 'radios',
            '#title' => $this->t('Gender'),
            '#options' => array('Male' => t('Male'), 'Female' => t('Female')),
            '#required' => true,
            '#attributes' => array(
                //'class' => array('form-control')
            ),
            '#default_value' => $user->get('field_gender')->value
        );

        if ($type == "contestant") {
            $measurement_default = 'US Standard';
            if ($user->get('field_length_unit')->value == "cm") {
                $measurement_default = 'Metric';
            }

            $form['measurement'] = array(
                '#type' => 'select',
                '#title' => $this->t('Measurement'),
                '#options' => array('US Standard' => 'US Standard', 'Metric' => 'Metric'), // Metric: cm, kg. US: Pound, inch
                '#required' => true,
                '#attributes' => array(
                    'class' => array('form-control')
                ),
                '#default_value' => $measurement_default
            );

            $form['eyes_color'] = array(
                '#type' => 'select',
                '#title' => $this->t('Eye Color'),
                '#options' => $this->getUserFieldValues('field_eyes_color'),
                '#required' => true,
                '#attributes' => array(
                    'class' => array('form-control')
                ),
                '#default_value' => $user->get('field_eyes_color')->value
            );

            $form['hair_color'] = array(
                '#type' => 'select',
                '#title' => $this->t('Hair Color'),
                '#options' => $this->getUserFieldValues('field_hair_color'),
                '#attributes' => array(
                    'class' => array('form-control')
                ),
                '#default_value' => $user->get('field_hair_color')->value
            );

            $form['height'] = array(
                '#type' => 'number',
                '#title' => $this->t('Height'),
                '#attributes' => array(
                    'class' => array('form-control')
                ),
                '#description' => '<div class="measurement-suffix"></div>',
                '#default_value' => $user->get('field_height')->value
            );

            $form['weight'] = array(
                '#type' => 'number',
                '#title' => $this->t('Weight'),
                '#attributes' => array(
                    'class' => array('form-control')
                ),
                '#description' => '<div class="measurement-suffix"></div>',
                '#default_value' => $user->get('field_weight')->value
            );

            $form['bust'] = array(
                '#type' => 'number',
                '#title' => $this->t('Bust'),
                '#attributes' => array(
                    'class' => array('form-control')
                ),
                '#description' => '<div class="measurement-suffix"></div>',
                '#default_value' => $user->get('field_bust')->value
            );

            $form['waist'] = array(
                '#type' => 'number',
                '#title' => $this->t('Waist'),
                '#attributes' => array(
                    'class' => array('form-control')
                ),
                '#description' => '<div class="measurement-suffix"></div>',
                '#default_value' => $user->get('field_waist')->value
            );

            $form['hips'] = array(
                '#type' => 'number',
                '#title' => $this->t('Hips'),
                '#attributes' => array(
                    'class' => array('form-control')
                ),
                '#description' => '<div class="measurement-suffix"></div>',
                '#default_value' => $user->get('field_hip')->value
            );

            $form['about_me'] = array(
                '#type' => 'textarea',
                '#title' => $this->t('More info about me'),
                '#attributes' => array(
                    'class' => array('form-control', 'about-me')
                ),
                '#default_value' => $user->get('field_about_me')->value
            );

            /*-------------------------
            Gallery up to 10 files
            */
            $gallery_count = $user->get('field_gallery')->count();
            $gallery_uploaded = array();
            if ($gallery_count) {
                for ($i = 0; $i < $gallery_count; $i++) {
                    $file_id = $user->get('field_gallery')->get($i)->getValue()['target_id'];
                    if (is_numeric($file_id) && $file_id) {
                        $file = File::load($file_id);
                        $file_url = file_create_url(file_url_transform_relative($file->getfileUri()));
                        $image_style_id = 'thumbnail';
                        $style = ImageStyle::load($image_style_id);
                        $image_url = file_url_transform_relative($style->buildUrl($file->getfileUri()));
                        $gallery_uploaded[$i] = '<div class="gallery-file gallery-'.$i.'"><a href="'.$file_url.'" target="_blank"><img src="'.$image_url.'" /></a></div>';
                    }
                }
            }

            $form['gallery_open'] = array(
                '#markup' => '<div class="gallery form-item-gallery">'
            );

            for ($i = 0; $i < 10; $i++) {
                $suffix = '';
                if (isset($gallery_uploaded[$i])) {
                    $suffix = $gallery_uploaded[$i];
                }

                $klass = 'gallery-file';
                $label = '';

                if ($i == 0) {
                    $label = 'Gallery';
                    $klass = 'gallery-file-first';
                }

                $form['gallery_'.$i] = array(
                    '#type' => 'file',
                    '#title' => $label,
                    '#attributes' => array('class' => array($klass)),
                    '#suffix' => $suffix
                );
            }

            $form['gallery_close'] = array(
                '#markup' => '</div>'
            );
        }

        // $form['type'] = array('#type' => 'hidden', '#value' => $type);

        $form['recaptcha'] = array(
            '#markup' => '<div id="g-recaptcha" class="g-recaptcha" data-sitekey="6LeJJCUTAAAAAFMG5QlQHzoguSOI1kmMAjIsMiAL"></div>'
        );
         
        $form['actions']['#type'] = 'actions';
            $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Update'),
            '#ajax' => array(
                'callback' => '::submitFormAjax',
            ),
        );

        $form['#title'] = 'My Profile';
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
            $user = \Drupal::currentUser();
            $user = user_load($user->id());

            // Validate email if not same as current email
            if ($user->get('mail')->value != $form_state->getValue('mail')) {
                // validate email
                if (user_load_by_mail($form_state->getValue('mail')) && $form_state->getValue('mail') != false) {
                    $response->addCommand(new HtmlCommand('.form-item-mail .description', 'Email already exist!'));
                    //$response->addCommand(new InvokeCommand('#edit-mail', 'css', array('color', '#ff0000')));
                } else {
                    $response->addCommand(new HtmlCommand('.form-item-mail .description', ''));
                }
            }
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

        $user = \Drupal::currentUser();
        $user = user_load($user->id());

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
            $message[] = 'Invalid email adress.';
        } else {
            if ($user->get('mail')->value != $form_state->getValue('mail')) {
                if (user_load_by_mail($form_state->getValue('mail')) && $form_state->getValue('mail') != false) {
                    $message[] = 'Email already exist.';
                }
            }
        }

        // validate first and last name
        if (!$values['first_name']) {
            $message[] = 'First name is required.';
        }

        if (!$values['last_name']) {
            $message[] = 'Last name is required.';
        }

        // validate birthday
        $birthday = $values['birthday'];
        $year = date('Y', strtotime($birthday));
        $current_year = date('Y', time());
        if (($current_year - $year) < 18) {
            $message[] = 'Your age must be greater than 18.';    
        }

        $birthday = $values['birthday'];
        $year = date('Y-m-d', strtotime($birthday));

        if (!$values['country']) {
            $message[] = 'Country is required.';
        }

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

        if (!$json->success) {
            //$response->addCommand(new HtmlCommand('.validate', "Please verify you're human by click on \"I'm not a robot\""));
            //return $response;
        }

        // ----------------------------------
        // save user when all fields are fine
        $language = \Drupal::languageManager()->getCurrentLanguage()->getId();

        // Mandatory settings
        //$user->setEmail($form_state->getValue('mail'));
        //$user->setUsername($form_state->getValue('mail'));

        // Optional settings
        /*
        $user->set("init", $form_state->getValue('mail'));
        $user->set("langcode", $language);
        $user->set("preferred_langcode", $language);
        $user->set("preferred_admin_langcode", $language);*/

        $roles = $user->getRoles();
        if (in_array('contestant', $roles)) {
            $type = 'contestant';
        }

        // custom fields
        $user->set("field_gender", $form_state->getValue('gender'));
        $user->set("field_birthday", $birthday);
        $user->set("field_first_name", $form_state->getValue('first_name'));
        $user->set("field_last_name", $form_state->getValue('last_name'));
        $user->set("field_country", $form_state->getValue('country'));
        // $user->set("field_age", date('Y', time()) - date('Y', strtotime($birthday)));
        $user->set("field_about_me", $form_state->getValue('about_me'));

        // these fields are for contestant only
        if ($type != "voter") {
            $user->set("field_bust", $form_state->getValue('bust'));
            $user->set("field_eyes_color", $form_state->getValue('eyes_color'));
            $user->set("field_waist", $form_state->getValue('waist'));
            $user->set("field_weight", $form_state->getValue('weight'));       
            
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

            $file = file_save_upload('photo_id');
            if ($file) {
                // set status permanent
                $file[0]->setPermanent();
                $file[0]->save();
                // move file from temporary:// to public://
                $file = file_move($file[0], 'public://'.$file[0]->getFilename());
                // set photo to user
                @$user->set('field_photo_id', array('target_id' => $file->id()));
            }

            // gallery
            $gallery_target = array();
            for ($i = 0; $i < 10; $i++) {
                $file = file_save_upload('gallery_'.$i);
                if ($file) {
                    $file[0]->setPermanent();
                    $file[0]->save();
                    // move file from temporary:// to public://
                    $file = file_move($file[0], 'public://'.$file[0]->getFilename());
                    //$gallery_target[] = $file->id();
                    $user->field_gallery[] = $file->id();
                }
            }
        }

        // $user->activate();
        // save user
        $user->save();
        
        // open message dialog
        $message = 'Profile update successully.';
        // Append script into callback message
        $script = '<script>
            swal("Profile Updated", "Profile update successully.", "success");
            jQuery(".sweet-alert").center();
            setTimeout(function() { jQuery(".my-account").trigger("click"); }, 3000);
        </script>';
        // $response->addCommand(new OpenModalDialogCommand('Thank you', $message), ['width' => '700', 'clkass' => 'dialog-thanks']);
        $response->addCommand(new HtmlCommand('.ui-dialog-title', $script));

        // -------------
        // close dialog
        $response->addCommand(new CloseDialogCommand('.dialog-edit-my-account'));

        return $response;
    }
}
