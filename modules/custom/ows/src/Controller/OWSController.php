<?php

/**
 @file
 Contains \Drupal\ows\Controller\OWSController.
 */

namespace Drupal\ows\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;

class OWSController extends ControllerBase
{
	public function homepage() {
		$account = \Drupal::currentUser();
		if (!empty($account->uid)) {
		} else {

		}

		// dialog property
		$dialog_enter_contest = json_encode(array(
			'title' => 'Enter the Contest',
			'width' => '650',
			'dialogClass' => 'dialog-enter-contest',
			'defaultDialog' => true
		));

		$dialog_vote = json_encode(array(
			'title' => 'Vote',
			'width' => '80%',
			'dialogClass' => 'dialog-vote',
		));

		$dialog_browse = json_encode(array(
			'title' => 'Browse',
			'width' => '80%',
			'dialogClass' => 'dialog-browse',
		));
		
		// $html = "<a href='/enter-contest' class='use-ajax' data-accepts='application/vnd.drupal-modal' data-dialog-type='modal' data-dialog-options='".$enter_contest."'>Go2</a>";
		
		// buttons open dialog
		$html = '<div class="load-container"><div class="loader">Loading...</div></div>';
		$html .= "<div class='dialog-buttons-wrapper hidden'>
			<a href='/enter-contest' id='btn-enter-contest' class='button button-red use-ajax' data-accepts='application/vnd.drupal-modal' data-dialog-type='modal' data-dialog-options='".$dialog_enter_contest."'>Enter the Contest</a>

			<a href='/vote' id='btn-vote' class='button button-red use-ajax' data-accepts='application/vnd.drupal-modal' data-dialog-type='modal' data-dialog-options='".$dialog_vote."'>Vote in the Contest</a>

			<a href='/browse' id='btn-browse' class='button button-red use-ajax' data-accepts='application/vnd.drupal-modal' data-dialog-type='modal' data-dialog-options='".$dialog_browse."'>Browse the Website</a>
		</div>";

		// sweet alert box
		$html .= '
		<div id="welcome-box" class="" title="Welcome to OWS">
			<div class="welcome-wrapper">
  				<div class="welcome-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>';

		$html .= "
  				<div class='buttons'>
  					<a href='#enter-contest' id='swal-btn-register' class='button button-red'>Enter the Contest</a>
  					<a href='#vote' id='swal-btn-sign' class='button button-red'>Vote in the Contest</a>
  					<a href='#browse' id='swal-btn-browse' class='button button-red browse-website'>Browse the Website</a>
  				</div>
  			</div>
		</div>";


		// <a id='btn-enter-contest' class='use-ajax button button-red enter-contest' href='/enter-contest' data-accepts='application/vnd.drupal-modal' data-dialog-type='modal' data-dialog-options='".$enter_contest."'>Enter the Contest</a>

		// $html .= '<div class="dialog">Homepage test</div>';

		//$user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
		//kint($user);

		return array('#type' => 'markup', '#markup' => $html);
	}

    public function ajaxContent() {
    	$type = $_REQUEST['type'];

		if ($type == "register") {
	    	// get user register form
	    	$entity = \Drupal::entityManager()->getStorage('user')->create(array());
	    	$formObject = \Drupal::entityManager()->getFormObject('user', 'register')->setEntity($entity);
			$form = \Drupal::formBuilder()->getForm($formObject);

			$form['#attached']['library'][] = 'core/drupal.dialog.ajax';
			
			// Adjust form fields
			// only allow contestant role (use css to hide this field)
			$form['account']['roles']['#options'] = array(
				'contestant' => "Contestant"
			);
			unset($form['account']['roles']['administrator']);
			unset($form['account']['roles']['voter']);

			unset($form['account']['notify']);
			unset($form['timezone']);
			unset($form['contact']);

			$form['terms'] = array(
				'#type' => 'checkbox',
				'#title' => 'Accept terms',
				'#required' => true,
				'#weight' => 20
			);

			$form['rule'] = array(
				'#type' => 'checkbox',
				'#title' => 'Accept rule',
				'#required' => true,
				'#weight' => 21
			);

			// avoid empty "action" rendered form
			/*
			$form['#action'] = '/user/register';
			$t = $form['actions']['submit']['#submit'];
			unset($form['actions']['submit']['#submit']);
			$form['actions']['submit']['#ajax'] = $t;
			$form['actions']['submit']['#ajax']['accepts'] = 'application/vnd.drupal-modal';

			$form['actions']['submit']['#ajax_processed'] = true;
			*/
			/*
			$form['actions'] = array();
			$form['actions']['#type'] = 'actions';
	        $form['actions']['submit'] = array(
	            '#type' => 'submit',
	            '#value' => $this->t('Register'),
	            '#ajax' => array(
	                'callback' => 'Drupal\ows\Form\EnterContestForm::submitForm',
	            ),
	            '#submit' => 'Drupal\ows\Form\EnterContestForm::submitFormDrupal\ows\Form\EnterContestForm::submitForm'
	        );*/

			kint($form);
			return $form;

			// custom form
			$form = \Drupal::formBuilder()->getForm('\Drupal\ows\JoinContestForm');
			return $form;
		} elseif ($type == "browse") {
			// ========================
			// Get browse views
			$view = \Drupal::service('renderer')->render(views_embed_view('browse', 'default'));
			if ($view) $html = $view->__toString();
		    return array('#type' => 'markup', '#markup' => '$html');
		} else if ($type == "view-contestant") {
			print $_REQUEST['uid'];
			return $this->contestantInfo($_REQUEST['id']);
		}

		return array('#type' => 'markup', '#markup' => 'Hello');
    }

    public function content() {
    	return array('#type' => 'markup', '#markup' => 'Content');
    }

    public function contestantInfo($uid) {
    	$user = user_load($uid);
    	// kint ($user);
    	if ($user->uid) {
     		$full_name = $user->get('field_first_name')->value. ' '.$user->get('field_last_name')->value;
		
			// user photo
			if (!empty($user->user_picture->target_id)) {
				$file = File::load($user->user_picture->target_id);
				$image_style_id = 'photo_large'; //$this->config('core.entity_view_display.user.user.compact')->get('content.user_picture.settings.image_style');
    			$style = ImageStyle::load($image_style_id);
    			$image_url = file_url_transform_relative($style->buildUrl($file->getfileUri()));
    			$alt_text = 'Profile picture for user ' . $user->getUsername();
			}
			
			// country
			$country = false;
			if (!empty($user->get('field_country')->value)) {
				$t = $user->get('field_country')->value;
				$country = $user->getFieldDefinitions()['field_country']->getItemDefinition()->getSettings()['allowed_values'];
				foreach($country as  $k => $v) {
					if ($t == $k) {
						$country = $v;
						break;
					}
				}
			}

			// birthday
			$birthday = false;
			$age = ' ';
			$height = $weight = $bust = $hair = $eye = '';
			if (!empty($user->get('field_birthday')->value)) {
				$birthday = date('d M Y', strtotime($user->get('field_birthday')->value));
				$age = (date('Y', time()) - date('Y', strtotime($birthday)));
			}

			$measurements = '';
			// waist
			if (!empty($user->get('field_waist')->value)) {
				$waist = $user->get('field_waist')->value;
				$measurements = $waist;
			}

			// hip
			if (!empty($user->get('field_hip')->value)) {
				$hip = $user->get('field_hip')->value;
				if ($measurements) $measurements .= ' - ' . $hip;
				else $measurements = 'Hip: '.$hip;
			}

			// height
			if (!empty($user->get('field_height')->value)) {
				$height = $user->get('field_height')->value;
			}
			
			// weight
			if (!empty($user->get('field_weight')->value)) {
				$weight = $user->get('field_weight')->value;
			}
			
			// bust
			if (!empty($user->get('field_bust')->value)) {
				$bust = $user->get('field_bust')->value;
			}
			
			// hair
			if (!empty($user->get('field_hair_color')->value)) {
				$hair = $user->get('field_hair_color')->value;
			}
			
			// eyes
			if (!empty($user->get('field_eyes_color')->value)) {
				$eyes = $user->get('field_eyes_color')->value;
			}

			// about me
			$about_me = '';
			if (!empty($user->get('field_about_me')->value)) {
				$about_me = $user->get('field_about_me')->value;
			}

	    	$html = '<div class="contestant-info" id="contestant-'.$uid.'">
	    		<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#personal-information">Personal Information</a></li>
					<li><a data-toggle="tab" href="#about-me">More info about me</a></li>
					<li><a data-toggle="tab" href="#">Invite</a></li>
				</ul>

				<div class="tab-content">
					<div id="personal-information" class="info personal-information tab-pane fade in active">
						<div class="photo"><img src="'.$image_url.'"/></div>
						<div class="detail">
							<div class="item">
								<span class="name">Full name</span>
								<span class="name value">'.$full_name.'</span>
							</div>

							<div class="item">
								<span class="name">Country</span>
								<span class="name value">'.$country.'</span>
							</div>

							<div class="item">
								<span class="name">Date of Birth</span>
								<span class="name value">'.$birthday.'</span>
							</div>

							<div class="item">
								<span class="name">Age</span>
								<span class="name value">'.$age.'</span>
							</div>

							<div class="item">
								<span class="name">Hip</span>
								<span class="name value">'.$hip.'</span>
							</div>

							<div class="item">
								<span class="name">Height</span>
								<span class="name value">'.$height.'</span>
							</div>

							<div class="item">
								<span class="name">Weight</span>
								<span class="name value">'.$weight.'</span>
							</div>

							<div class="item">
								<span class="name">Burst</span>
								<span class="name value">'.$bsut.'</span>
							</div>

							<div class="item">
								<span class="name">Hair Color</span>
								<span class="name value">'.$hair.'</span>
							</div>

							<div class="item">
								<span class="name">Eye Color</span>
								<span class="name value">'.$eyes.'</span>
							</div>

							<div class="item">
								<span class="name">Measurements</span>
								<span class="name value">'.$measurements.'</span>
							</div>
						</div>
					</div>

					<div id="about-me" class="info personal-information tab-pane fade in">
						'.$about_me.'
					</div>

					<div id="invite" class="info personal-information tab-pane fade in">
						eeeee
					</div>
				</div>
	    	</div>';
    	}

    	return array('#type' => 'markup', '#markup' => $html);
    }
}
