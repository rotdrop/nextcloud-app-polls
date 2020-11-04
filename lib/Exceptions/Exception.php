<?php
/**
 * @copyright Copyright (c) 2020 René Gieling <github@dartcafe.de>
 *
 * @author René Gieling <github@dartcafe.de>
 *
 * @license GNU AGPL version 3 or any later version
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Polls\Exceptions;

use OCP\AppFramework\Http;

class Exception extends \Exception {

	/** @var integer */
	protected $status;

	/**
	 * Exception Constructor
	 * @param string $e exception message
	 */
	public function __construct(
		$e = 'Unexpected error',
		$status = Http::STATUS_STATUS_INTERNAL_SERVER_ERROR
	) {
		parent::__construct($e);
		$this->status = $status;
	}
	public function getStatus() {
		return $this->status;
	}
}
