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

use Symfony\Component\HttpFoundation\JsonResponse;

class OWSController extends ControllerBase
{
	public function homepage() {
		// -------------------
		// close site, only show message and a form for user to enter email, name
		// remember to add block Main menu to header on live
		$close_domains = array('owds.dd', 'officialworldssexiest.com');
		if (in_array($_SERVER['SERVER_NAME'], $close_domains)) {
			// -------------------
			// dialog property
			$dialog_add_me = json_encode(array(
				'title' => 'Add my name',
				'width' => '360',
				'height' => '240',
				'dialogClass' => 'dialog-add-me dialog-default',
				'defaultDialog' => true
			));
			
			// buttons open dialog
			$html .= "<div class='dialog-buttons-wrapper hidden'>
				<a href='/add-me' id='btn-add-me' class='button button-red use-ajax' data-accepts='application/vnd.drupal-modal' data-dialog-type='modal' data-dialog-options='".$dialog_add_me."'>Add my name</a>

			</div>";

			$intro = node_load(7);
			$intro_text = $intro->get('body')->value;
			// sweet alert box
			$html .= '
			<div id="welcome-box" class="swal-add-me" class="hidden" title="The Official World’s Sexiest Contest">
				<div class="welcome-wrapper">
	  				<div class="welcome-text add-me-text">
						'.$intro_text.'
	  				</div>';

			$html .= "
	  				<div class='buttons'>
	  					<a href='#add-me' id='swal-btn-add-me' class='button button-red'>Add my name</a>
	  				</div>
	  			</div>
			</div>";
			
			return array('#type' => 'markup', '#markup' => $html);
		}

		// -------------------
		// normal site
		$account = \Drupal::currentUser();

		$html = ''; //<div class="load-container"><div class="load-wrapper"><div class="loader">Loading...</div></div></div>

		// user not logged
		// if (!empty($account->id())) {}

		// -------------------
		// dialog property
		$dialog_enter_contest = json_encode(array(
			'title' => 'Enter the Contest',
			'width' => '680',
			'height' => '600',
			'dialogClass' => 'dialog-enter-contest dialog-default',
			'defaultDialog' => true
		));

		$dialog_vote = json_encode(array(
			'title' => 'Register to Vote',
			'width' => '680',
			'dialogClass' => 'dialog-vote dialog-default',
			'defaultDialog' => true
		));

		$dialog_browse = json_encode(array(
			'title' => 'Browse',
			'width' => '680',
			'dialogClass' => 'dialog-browse',
		));

		// invite friend
		$dialog_invite = json_encode(array(
			'title' => 'Invite Friend',
			'width' => '680',
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
		$intro = node_load(7);
		$intro_text = $intro->get('body')->value;
		$html .= '
		<div id="welcome-box" class="hidden" title="The Official World\'s Sexiest Contest" class="ows-welcome">
			<div class="welcome-wrapper">
  				<div class="welcome-text">'.$intro_text.'</div>';

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
			'width' => '680',
			'dialogClass' => 'dialog-browse dialog-men dialog-default',
			'defaultDialog' => true
		));

		$dialog_women = json_encode(array(
			'title' => 'Women',
			'width' => '680',
			'dialogClass' => 'dialog-browse dialog-women dialog-default',
			'defaultDialog' => true
		));

		$dialog_things = json_encode(array(
			'title' => 'Things',
			'width' => '680',
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

			// ========================
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
		    return array('#type' => 'markup', '#markup' => $html);

		} else if ($type == "view-contestant") {

			// ========================
			// View a contestant
			return $this->contestantInfo($_REQUEST['id']);

		} else if ($type == "view-page") {

			// ========================
			// load a static page
			$nid = str_replace('node/', '', $_REQUEST['page']);
			$html = 'Page not found!';
			if (($nid)) {
				$node = node_load($nid);
				if ($node->id()) {
					$body = $node->get('body')->value;
					$html = $body;
				}
			}

			return array('#type' => 'markup', '#markup' => $html);

		} elseif ($type == "voting") {

			// ========================
			// Voter voting a contestant
			$score = $_POST['score'];
			$contestant = $_POST['contestant'];
			$result = $this->votingContestant($contestant, $score);			

			return new JsonResponse($result);

		} elseif ($type == "voting-update") {

			// ========================
			// Voter update
			$score = $_POST['score'];
			$contestant = $_POST['contestant'];
			$result = $this->votingContestantUpdate($contestant, $score);
			return new JsonResponse($result);

		} else if ($type == "vote-list") {
			
			// ========================
			// Vote list result
			$votes = array();
			$content = array();

		    $content['message'] = array(
		      '#markup' => $this->t('Vote list result.'),
		    );

		    $headers = array(
		    	'#',
		    	t('Contestant'),
		    	t('Score'),
		    	t('Last voter'),
		    	t('Last vote date'),
		    	'Total vote'
		    );

		    $rows = array();
		    $data = array();
		    foreach ($entries = \Drupal\ows\DbStorage::voteList() as $entry) {
		    	$data[$entry->contestant][] = $entry;
		    	// Sanitize each entry.
		    	//$rows[] = array_map('Drupal\Component\Utility\SafeMarkup::checkPlain', $entry);
		    }

		    $row_data = array();
		    foreach($data as $key => $val) {
		    	foreach ($val as $key1 => $val1) {
			    	if (!isset($row_data[$key]['score'])) $row_data[$key]['score'] = 0;
			    	if (!isset($row_data[$key]['created'])) $row_data[$key]['created'] = 0;
			    	if (!isset($row_data[$key]['count'])) $row_data[$key]['count'] = 0;

			    	$row_data[$key]['score'] += $val1->score;
			    	$row_data[$key]['count'] += 1;
			    	$row_data[$key]['contestant'] = $val1->contestant;
			    	$row_data[$key]['uid'] = $val1->uid;
			    	// store latest voter
			    	if ($val1->created > $row_data[$key]['created']) {
			    		$row_data[$key]['created'] = $val1->created;
			    		$row_data[$key]['last'] = $val1->uid;
			    	}
		    	}
		    }

		    // calculate score
		    foreach ($row_data as $key => $value) {
		    	$row_data[$key]['score'] = ($row_data[$key]['score'] / $row_data[$key]['count']);
		    }

			// sort array based on score
			$this->sort_array_of_array($row_data, "score");
			usort($row_data, function($a,$b) { return $a['optionNumber'] - $b['optionNumber'];} );

			// add data to table
			$index = 1;
		    foreach ($row_data as $key => $value) {
		    	$contestant = user_load($value['contestant']);
		    	$full_name = '';
		    	if (is_object($contestant)) $full_name .= $contestant->get('field_first_name')->value.' ';
		    	if (is_object($contestant)) $full_name .= $contestant->get('field_last_name')->value;

				$voter = user_load($value['uid']);
		    	if (is_object($voter)) $voter_name .= $voter->get('field_first_name')->value.' ';
		    	if (is_object($voter)) $voter_name .= $voter->get('field_last_name')->value;

		    	$rows[] = array($index, $full_name, $value['score'], $voter_name, date('d-m-Y', $value['created']), $value['count']);
		    	$index++;
		    	//'<a data-dialog-type="modal" data-accepts="application/vnd.drupal-modal" id="contestant-'.$value['contestant'].'" class="browse-contestant" href="#contestant/'.$value['contestant'].'">'
		    }

		    $content['table'] = array(
		    	'#type' => 'table',
		    	'#header' => $headers,
		    	'#rows' => $rows,
		    	'#attributes' => array('id' => 'dbtng-example-advanced-list'),
		    	'#empty' => t('No entries available.'),
		    );

		    // Don't cache this page.
		    $content['#cache']['max-age'] = 0;
		    return $content;

		} else if ($type == "my-vote") {

			// ========================
			// My votes
			$votes = array();
			$content = array();

		    /*$content['message'] = array(
		      '#markup' => $this->t('My vote.'),
		    );*/

		    $headers = array(
		    	'#',
		    	t('Voter'),
		    	t('Score'),
		    	t('Last vote date')
		    );

		    $rows = array();
		    $data = array();
		    $index = 1;

		    $account = \Drupal::currentUser();
		    if (is_object($account)) {
			    foreach ($entries = \Drupal\ows\DbStorage::contestantVoteList(14/*$account->id()*/) as $entry) {
			    	$voter = user_load($entry->uid);
			    	if (is_object($voter)) $voter_name .= $voter->get('field_first_name')->value.' ';
			    	if (is_object($voter)) $voter_name .= $voter->get('field_last_name')->value;

			    	$rows[] = array($index, $voter_name, $value['score'], date('d-m-Y', $entry->created));
			    	$index++;
			    }
			}

		    $content['table'] = array(
		    	'#type' => 'table',
		    	'#header' => $headers,
		    	'#rows' => $rows,
		    	'#attributes' => array('id' => 'contestant-vote-list'),
		    	'#empty' => t('No vote record.'),
		    );

		    // Don't cache this page.
		    $content['#cache']['max-age'] = 0;
		    return $content;
		    
		} else {
			return array('#type' => 'markup', '#markup' => 'Page not found!');
		}
    }
 	
 	// sort array
	public function sort_array_of_array(&$array, $subfield) {
	    $sortarray = array();
	    foreach ($array as $key => $row) {
	        $sortarray[$key] = $row[$subfield];
	    }

	    array_multisort($sortarray, SORT_ASC, $array);
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

			// gallery
			$gallery = '';
			if ($user->get('field_gallery')->count()) {
				$gallery_photos = $user->get('field_gallery');
				for ($i = 0; $i < $gallery_photos->count(); $i++) {
					$uri = $gallery_photos->get($i)->get('entity')->getTarget()->getValue()->getFileUri();
		
				    $photo_thumbnail = ImageStyle::load('thumbnail')->buildUrl($uri);
				    $photo_full = ImageStyle::load('gallery_full')->buildUrl($uri);

					$gallery .= '<div class="col-md-2 col-xs-6 item">
						<a href="'.$photo_full.'" class="colorbox" rel="gallery-item"><img src="'.$photo_thumbnail.'" /></a>
					</div>';
					if ($i == 5) {
						$gallery .= '<div class="clearfix"></div>';
					}
				}
			}

			// videos
			$videos = '';
			if ($user->get('field_videos')->count()) {
				$gallery_videos = $user->get('field_videos');
				for ($i = 0; $i < $gallery_videos->count(); $i++) {
					$file = $gallery_videos->get($i)->get('entity')->getTarget()->getValue();
					$uri = $file->getFileUri();
					$video_path = $file->url();				
					$file_info = pathinfo($uri);
					$file_name = $file_info['filename'];
					$source_file = drupal_realpath($uri);
					$dest_file = drupal_realpath('public://screenshot_'.$file_name.'.jpg');
					
					$thumbnail_uri = 'public://screenshot_'.$file_name.'.jpg';
					if (!file_exists($thumbnail_uri)) {
						$FFMPEG_EXE = str_replace('\\', '/', drupal_realpath(drupal_get_path('module', 'ows')).'/ffmpeg/ffmpeg.exe');
						$command = $FFMPEG_EXE.' -i '.$source_file.' -ss 5 -vframes 50 '.$dest_file;
						$result = exec($command);
					}

				    $video_thumbnail = ImageStyle::load('video_thumbnail')->buildUrl("public://".basename($thumbnail_uri));				    
				    $video_mime = 'mp4';

				    // <a href="#video-player" data-vidID="contestant_video_player" class="play-video" vid="'.$file->id().'" type="'.$file->getMimeType().'" vfile="'.$file->url().'" rel="video"><img src="'.$video_thumbnail.'"><span></span></a>
					$videos .= '<div class="col-md-12 col-xs-12 item">
						<div id="video-player-'.$file->id().'" class="video-player" video-id="'.$file->id().'" video-type="'.$file->getMimeType().'" video-file="'.$file->url().'" video-thumb="'.$video_thumbnail.'"></div>
					</div>';
				}
			}

			// Voting slider
			$vote_slider = '';
			$account = \Drupal::currentUser();

			$voting_score = 0;
			$query = db_select('vote', 'v');
			$query->fields('v');
			$query->condition('v.contestant', $uid);
		    $result = $query->execute();
		    $total_vote = 0;
		    $voted_score = 0;
		    foreach($result as $r) {
		    	$voting_score += $r->score;
		    	$total_vote++;
		    	if ($r->uid == $account->id()) {
		    		$voted_score = $r->score;
		    	}
		    }

		    $voting_score = $voting_score / $total_vote;

			// user not logged
			if (!empty($account->id())) {
				if (in_array('voter', $account->getRoles())) {
					$vote_slider = '<div class="clearfix"></div>
					<div class="voting-contestant">
						<h4 class="conestant">Vote for '.$full_name.'</h4>
						<div class="voting-container" score="'.$voted_score.'"></div>
					</div>';
				}
			}			

	    	$html = '<div class="contestant-info" id="contestant-'.$uid.'">
	    		<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#personal-information-'.$uid.'">Personal Information</a></li>
					<li><a data-toggle="tab" href="#about-me-'.$uid.'">About me</a></li>
					<li><a data-toggle="tab" href="#gallery-'.$uid.'">Gallery</a></li>
					<li><a data-toggle="tab" href="#videos-'.$uid.'">Videos</a></li>
					<li><a data-toggle="tab" href="#invite-'.$uid.'">Invite</a></li>
				</ul>

				<div class="tab-content">
					<div id="personal-information-'.$uid.'" class="info personal-information tab-pane fade in active">
						<div class="photo">
							<img class="country-flag flag-'.strtolower($country).'" src="themes/ows_theme/images/flags/'.$country.'.png" />
							<img src="'.$image_url.'" />
							<div class="vote-score">Voting Score: <span>'.$voting_score.'<span></div>
						</div>
						<div class="detail">
							<div class="item">
								<span class="name full-name">Full name: </span>
								<span class="name value">'.$full_name.'</span>
							</div>

							<div class="item country" style="display:none;">
								<span class="name">Country: </span>
								<span class="name value">'.$country.'</span>
							</div>

							<div class="item birthday">
								<span class="name">Date of Birth: </span>
								<span class="name value">'.$birthday.'</span>
							</div>

							<div class="item age">
								<span class="name">Age: </span>
								<span class="name value">'.$age.'</span>
							</div>

							<div class="item hip">
								<span class="name">Hip: </span>
								<span class="name value">'.$hip.'</span>
							</div>

							<div class="item height">
								<span class="name">Height: </span>
								<span class="name value">'.$height.'</span>
							</div>

							<div class="item weight">
								<span class="name">Weight: </span>
								<span class="name value">'.$weight.'</span>
							</div>

							<div class="item burst">
								<span class="name">Burst: </span>
								<span class="name value">'.$bust.'</span>
							</div>

							<div class="item hair">
								<span class="name">Hair Color: </span>
								<span class="name value">'.$hair.'</span>
							</div>

							<div class="item eye">
								<span class="name">Eye Color: </span>
								<span class="name value">'.$eyes.'</span>
							</div>

							<div class="item">
								<span class="name">Measurements: </span>
								<span class="name value">'.$measurements.'</span>
							</div>
						</div>

						'.$vote_slider.'
					</div>

					<div id="about-me-'.$uid.'" class="info tab-pane fade in">'.$about_me.'</div>
					<div id="gallery-'.$uid.'" class="gallery tab-pane fade in">'.$gallery.'</div>
					<div id="videos-'.$uid.'" class="videos tab-pane fade in">'.$videos.'</div>
					<div id="invite-'.$uid.'" class="invite-friend-form tab-pane fade in"></div>
				</div>
	    	</div>'; //<a href="#invite-friend" id="dialog-btn-invite-friend" class="button button-red invite-friend">Invite Friend</a>
    	}

    	return array('#type' => 'markup', '#markup' => $html);
    }

    public function votingContestant($contestant, $score) {
    	$voter = \Drupal::currentUser();
    	$contestant = user_load($contestant);
    	$gender = $contestant->get('field_gender')->value;
    	$message = '';
    	$code = 1;
    	$case = 'insert';
    	// check exist vote
    	$query = db_select('vote', 'v');
		$query->fields('v');
		$query->condition('v.uid', $voter->id());
		$query->condition('v.contestant', $contestant->id());
	    $result = $query->execute();

	    foreach($result as $r) {
	    	$fields = array('uid' => $voter->id(), 'contestant' => $contestant->id(), 'score' => $score, 'created' => time());
    		$query = db_update('vote')->fields($fields);
    		$query->condition('uid', $voter->id());
			$query->condition('contestant', $contestant->id());
			$query->execute();

    		$case = 'update';
    		$message = 'Vote score has been updated.';
	    }

	    if ($case == "insert") {
	    	// score 100
			if ($score == 100) {
				// each voter can vote 100 for one Men and one Woman
				$query = db_select('vote', 'v');
				$query->fields('v');
				$query->condition('v.uid', $voter->id());
				$query->condition('v.score', $score);
			    $result = $query->execute();
			    
			    $voted_result = array();
			    foreach($result as $r) {
			    	// $voted_result[] = $r;
			    	$u = user_load($r->contestant);
			    	if ($u->id()) {
				    	$u_gender = $u->get('field_gender')->value;
				    	if ($u_gender == $gender) {
				    		$name = $u->get('field_first_name')->value.' '.$u->get('field_last_name')->value;
				    		$message = "You have voted score 100 for $name, you can only vote 100 for one Men and one Woman only. Do you want to replace score of $name to 99?";
				    		$code = 2;
				    	}
			    	}
			    } 

			    // OK insert vote data
			    if ($code == 0) {
			    	$fields = array('uid' => $voter->id(), 'contestant' => $contestant->id(), 'score' => $score, 'created' => time());
	        		db_insert('vote')->fields($fields)->execute();
	        		$message = 'Vote store has been recorded.';
			    }
			} else {
				$fields = array('uid' => $voter->id(), 'contestant' => $contestant->id(), 'score' => $score, 'created' => time());
	        	db_insert('vote')->fields($fields)->execute();
	        	$message = 'Vote store has been recorded.';
			}			
		}

		return array('code' => $code, 'message' => $message);
    }

    public function votingContestantUpdate($contestant, $score) {
    	$voter = \Drupal::currentUser();
    	$contestant = user_load($contestant);

    	// get previous contestant to set as 99
    	$query = db_select('vote', 'v');
		$query->fields('v');
		$query->condition('v.uid', $voter->id());
		$query->condition('v.score', $score);
	    $result = $query->execute();

	    $voted_result = array();
	    foreach($result as $r) {
	    	$fields = array('score' => 99, 'created' => time());
    		$query = db_update('vote')->fields($fields);
    		$query->condition('id', $r->id);
			$query->execute();
	    }

	    // insert new record
		$fields = array('uid' => $voter->id(), 'contestant' => $contestant->id(), 'score' => $score, 'created' => time());
	     	db_insert('vote')->fields($fields)->execute();

	    return array('code' => 1, 'message' => 'Vote score has been updated.');
    }

    public function playVideo($id) {
    	$html = '';
    	if (is_numeric($id)) {
    		$file = file_load($id);
    		$html = $file->url();
    	}

    	return array('#type' => 'markup', '#markup' => $html);
    }

    public function playThumbnail($id) {
    	return $id;
    }
}
