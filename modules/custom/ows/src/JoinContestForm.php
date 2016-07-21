<?php
/**
* @file
* Contains \Drupal\ows\JoinContestForm
*/
namespace Drupal\ows;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class JoinContestForm extends FormBase {
	public function getFormId() {
    	return 'join_contest_form';
	}

	public function buildForm(array $form, FormStateInterface $form_state) {
		$form = array();

		$form['header'] = array('#markup' => '<h2 class="dialog-title">Enter Contest</h2>');

		$form['first_name'] = array(
			'#type' => 'textfield',
			'#title' => 'First Name',
			/*
			'#ajax' => array(
				// Function to call when event on form element triggered.
				'callback' => 'Drupal\ajax_example\Form\AjaxExampleForm::usernameValidateCallback',
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
			),*/
		);

		$form['last_name'] = array(
			'#type' => 'textfield',
			'#title' => 'Last Name',
			'#required' => true
		);

		$form['last_name'] = array(
			'#type' => 'textfield',
			'#title' => 'Last Name',
			'#required' => true
		);

		$form['email'] = array(
			'#type' => 'email',
			'#title' => 'Email',
			'#required' => true
		);

		$form['birthday'] = array(
			'#type' => 'date',
			'#title' => 'Birthday',
			// '#default_value' => array('year' => 2020, 'month' => 2, 'day' => 15,)
		);

		$form['gender'] = array(
			'#type' => 'select',
			'#title' => 'Gender',
			'#options' => array(
				'Male' => 'Male',
				'Female' => 'Female'
			),
			'#required' => TRUE,
		);

		$form['height'] = array(
			'#type' => 'textfield',
			'#title' => 'Height'
		);

		$form['weight'] = array(
			'#type' => 'textfield',
			'#title' => 'Weight'
		);

		$form['eye_color'] = array(
			'#type' => 'textfield',
			'#title' => 'Eye Color'
		);

		$form['hair'] = array(
			'#type' => 'textfield',
			'#title' => 'Hair Color'
		);

		$form['voice'] = array(
			'#type' => 'file',
			'#title' => 'Voice'
		);

		$form['terms'] = array(
			'#type' => 'checkbox',
			'#title' => 'Accept terms',
			'#required' => true
		);

		$form['rule'] = array(
			'#type' => 'checkbox',
			'#title' => 'Accept rule',
			'#required' => true
		);

		$form['voice'] = array(
			'#type' => 'file',
			'#title' => 'Voice'
		);

		$form['submit'] = array(
		    '#type' => 'submit', 
		    '#value' => t('Join Now!'),
		);

		return $form;
	}

	public function validateForm(array &$form, FormStateInterface $form_state) {
		/*if (strlen($form_state->getValue('phone_number')) < 3) {
			$form_state->setErrorByName('phone_number', $this->t('The phone number is too short. Please enter a full phone number.'));
		}*/
	}

	public function submitForm(array &$form, FormStateInterface $form_state) {
		$lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
    	$user = \Drupal\user\Entity\User::create();

    	$form_state->setRebuild(FALSE);
    	$user->setUsername($form_state->getValue('email'));
	    $user->setPassword('Bosco');
	    $user->setEmail($form_state->getValue('email'));
	    $user->enforceIsNew();  // Set this to FALSE if you want to edit (resave) an existing user object
	 
		// Optional settings  <-- Thanks to http://drupal8.ovh/ for these suggestions!
	    $user->set("init", $form_state->getValue(''));
	    $user->set("langcode", $lang);
	    $user->set("preferred_langcode", $lang);
	    $user->set("preferred_admin_langcode", $lang);
	    //$user->set("setting_name", 'setting_value');
	    //$user->addRole(10);
	    $user->roles = array(
	    	'authenticated' => 'Authenticated user',
	    	'contestant' => 'Contestant',
	    );
	    $user->activate();
	 	kint($user);
		// Save user
	    $result = $user->save();
	}

}

