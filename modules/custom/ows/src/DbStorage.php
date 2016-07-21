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

}

