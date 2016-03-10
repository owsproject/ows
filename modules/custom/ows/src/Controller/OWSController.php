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
  				</div>
  			</div>
		</div>';

		$html .= '<div class="dialog">Homepage</div>';

		return array('#type' => 'markup', '#markup' => $html);
	}

    public function ajaxContent() {
    	$type = $_REQUEST['type'];
    	/*$form['name'] = array(
		    '#type' => 'textfield',
		    '#title' => t('Name'),
		);*/

    	// get user register form
    	/*$entity = \Drupal::entityManager()->getStorage('user')->create(array());
    	$formObject = \Drupal::entityManager()->getFormObject('user', 'register')->setEntity($entity);
		$form = \Drupal::formBuilder()->getForm($formObject);
		return $form;*/


		$form = \Drupal::formBuilder()->getForm('\Drupal\ows\JoinContestForm');
		return $form;
		
        return array('#type' => 'markup', '#markup' => $html);
    }
}
