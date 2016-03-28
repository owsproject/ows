<?php

/**
 @file
 Contains \Drupal\ows\Controller\OWSController.
 */

namespace Drupal\ows\Controller;

use Drupal\Core\Controller\ControllerBase;

class OWSController extends ControllerBase
{
	public function homepage() {
		/*<div id="splash-img" style="display:none;">
			<img src="/themes/ows_theme/images/splash.jpg" />
    	</div>

    	<div id="welcome-dialog" class="dialog" title="Welcome to OWS">
  			<p>Lotem istest.</p>
		</div>
		
		*/
		
		$account = \Drupal::currentUser();

		if (!$account->uid) {
			
		} else {

		}

		$html = '<div id="welcome-box" class="" title="Welcome to OWS">
			<div class="welcome-wrapper">
  				<div class="welcome-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
  				<div class="buttons">
  					<a href="#enter-content" id="btn-enter-contest" class="button button-red enter-contest">Enter the Contest</a>
  					<a href="#vote" id="btn-sign" class="button button-red vote-contest">Vote in the Contest</a>
  					<a href="#browse" id="btn-browse" class="button button-red browse-website">Browse the Website</a>

  					<a href="/user/register" id="btn-browse-s" class="button button-red use-ajax">Go</a>
  				</div>
  			</div>
		</div>';

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
			$form['#action'] = '/user/register';
			$t = $form['actions']['submit']['#submit'];
			unset($form['actions']['submit']['#submit']);
			$form['actions']['submit']['#ajax'] = $t;
			$form['actions']['submit']['#ajax']['accepts'] = 'application/vnd.drupal-modal';

			$form['actions']['submit']['#ajax_processed'] = true;

			kint($form);
			return $form;

			// custom form
			//$form = \Drupal::formBuilder()->getForm('\Drupal\ows\JoinContestForm');
			//return $form;
		} elseif ($type == "browse") {
			$view = \Drupal::service('renderer')->render(views_embed_view('browse', 'default'));
			if ($view) $html = $view->__toString();
		    return array('#type' => 'markup', '#markup' => $html);
		}

		return array('#type' => 'markup', '#markup' => 'Hello');
    }

    public function content() {
    	return array('#type' => 'markup', '#markup' => 'Content');
    }

}
