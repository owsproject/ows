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
        
        $form['mail'] = array(
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#ajax' => array(
                'callback' => array($this, 'validateMail'),
            )
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
        $valid = $this->validateEmail($form, $form_state);
        
        
    }
    /**
    * {@inheritdoc}
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $response = new AjaxResponse();
        
        //$response->addCommand(new CssCommand('#edit-mail', $css));
        //$response->addCommand(new HtmlCommand('.valid-message', $this->t('Email not valid.')));

        $response->addCommand(new OpenModalDialogCommand('Thank you', 'You have entered the contest!'));
        return $response;
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
