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

		$html = ''; //<div class="load-container"><div class="load-wrapper"><div class="loader">Loading...</div></div></div>

		// user not logged
		if (!empty($account->uid)) {
		}

		// -------------------
		// dialog property
		$dialog_enter_contest = json_encode(array(
			'title' => 'Enter the Contest',
			'width' => '650',
			'dialogClass' => 'dialog-enter-contest',
			'defaultDialog' => true
		));

		$dialog_vote = json_encode(array(
			'title' => 'Register to Vote',
			'width' => '650',
			'dialogClass' => 'dialog-vote',
			'defaultDialog' => true
		));

		$dialog_browse = json_encode(array(
			'title' => 'Browse',
			'width' => '80%',
			'dialogClass' => 'dialog-browse',
		));

		// invite friend
		$dialog_invite = json_encode(array(
			'title' => 'Invite Friend',
			'width' => '60%',
			'dialogClass' => 'dialog-invite',
			'defaultDialog' => true
		));
		
		// buttons open dialog
		$html .= "<div class='dialog-buttons-wrapper hidden'>
			<a href='/enter-contest' id='btn-enter-contest' class='button button-red use-ajax' data-accepts='application/vnd.drupal-modal' data-dialog-type='modal' data-dialog-options='".$dialog_enter_contest."'>Enter the Contest</a>

			<a href='/enter-contest?type=voter' id='btn-vote' class='button button-red use-ajax' data-accepts='application/vnd.drupal-modal' data-dialog-type='modal' data-dialog-options='".$dialog_vote."'>Vote in the Contest</a>

			<a href='/browse' id='btn-browse' class='button button-red use-ajax' data-accepts='application/vnd.drupal-modal' data-dialog-type='modal' data-dialog-options='".$dialog_browse."'>Browse the Website</a>

			<a href='/invite-friend' id='btn-invite-friend' class='button button-red use-ajax' data-accepts='application/vnd.drupal-modal' data-dialog-type='modal' data-dialog-options='".$dialog_invite."'>Invite Friend</a>
		</div>";

		// sweet alert box
		$html .= '
		<div id="welcome-box" class="hidden" title="Welcome to OWS">
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
  					<a href='#vote' id='swal-btn-vote' class='button button-red'>Vote in the Contest</a>
  					<a href='#browse' id='swal-btn-browse' class='button button-red browse-website'>Browse the Website</a>
  				</div>
  			</div>
		</div>";

		// -----------------
		$role = '';
		$roles = $account->getRoles();
		foreach($roles as $k => $v) {
			if ($v == "voter") {
				$role = "Voter";
				break;
			}

			if ($v == "contestant") {
				$role = 'Contestant';
				break;
			}
		}

		// dialog property
		$dialog_men = json_encode(array(
			'title' => 'Men',
			'width' => '650',
			'dialogClass' => 'dialog-browse dialog-men'
		));

		$dialog_women = json_encode(array(
			'title' => 'Women',
			'width' => '80%',
			'dialogClass' => 'dialog-browse dialog-women',
		));

		$dialog_things = json_encode(array(
			'title' => 'Things',
			'width' => '80%',
			'dialogClass' => 'dialog-browse dialog-things',
		));

		// buttons open dialog
		$html .= "<div class='dialog-buttons-wrapper hidden'>
			<a href='/browse/Male' id='btn-men' class='button button-red use-ajax' data-accepts='application/vnd.drupal-modal' data-dialog-type='modal' data-dialog-options='".$dialog_men."'>Men</a>

			<a href='/browse/Female' id='btn-women' class='button button-red use-ajax' data-accepts='application/vnd.drupal-modal' data-dialog-type='modal' data-dialog-options='".$dialog_women."'>Women</a>

			<a href='/browse/things' id='btn-things' class='button button-red use-ajax' data-accepts='application/vnd.drupal-modal' data-dialog-type='modal' data-dialog-options='".$dialog_things."'>Things</a>
		</div>";

		// sweet alert box
		$html .= '
		<div id="browse-box" class="hidden" title="Welcome to OWS">
			<div class="welcome-wrapper">
  				<div class="welcome-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>';

		$html .= "
  				<div class='buttons'>
  					<a href='#men' id='swal-btn-men' class='button button-red'>Men</a>
  					<a href='#women' id='swal-btn-women' class='button button-red'>Women</a>
  					<a href='#things' id='swal-btn-things' class='button button-red browse-website'>Things</a>
  				</div>
  			</div>
		</div>";
		
		return array('#type' => 'markup', '#markup' => $html);
	}

	public function inviteFriend() {
		$result = 0;

		$friend_name = $_POST['name'];
		$to = $_POST['email'];
		$friend_content = $_POST['content'];

		$mailManager = \Drupal::service('plugin.manager.mail');
		$module = 'ows';
		$key = 'invite_friend';
		
		$params['message'] = 'Dear '.$friend_name.'<br>
		Join and vote for me at OWS.';

		$params['title'] = "Dear $friend_name check out this hot contestant. Come join and vote like me!";
		$langcode = \Drupal::currentUser()->getPreferredLangcode();
		$send = true;
		$result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

		if ($result['result'] !== true) {
			$result = 0;
		} else {
			$result = 1;
		}

		return array('#type' => 'markup', '#markup' => $result);
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

			// kint($form);
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
					<li class="active"><a data-toggle="tab" href="#personal-information-'.$uid.'">Personal Information</a></li>
					<li><a data-toggle="tab" href="#about-me-'.$uid.'">More info about me</a></li>
					<li><a data-toggle="tab" href="#invite-'.$uid.'">Invite</a></li>
				</ul>

				<div class="tab-content">
					<div id="personal-information-'.$uid.'" class="info personal-information tab-pane fade in active">
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
								<span class="name value">'.$bust.'</span>
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

					<div id="about-me-'.$uid.'" class="info tab-pane fade in">
						'.$about_me.'
						<br>
						<a href="#invite-friend" id="dialog-btn-invite-friend" class="button button-red invite-friend">Invite Friend</a>
					</div>

					<div id="invite-'.$uid.'" class="invite-friend-form tab-pane fade in"></div>
				</div>
	    	</div>';
    	}

    	return array('#type' => 'markup', '#markup' => $html);
    }
}
