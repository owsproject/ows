<?php

/**
 * @file
 * Contains \Drupal\ows\DbStorage.
 */

namespace Drupal\ows;

/**
 * Class OWSStorage.
 */
class DbStorage {
	/* Vote */
	public static function voteList() {
	    $select = db_select('vote', 'v');
	    $select->fields('v');
	    // Return the result in object format.
	    return $select->execute()->fetchAll();
	}

	/* Vote list of an user */
	public static function contestantVoteList($contestant) {
	    $select = db_select('vote', 'v');
	    $select->condition('v.contestant', '=', $contestant);
	    $select->fields('v');
	    return $select->execute()->fetchAll();
	}
}

