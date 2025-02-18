<?php
/*
 * @copyright Copyright (c) 2021 René Gieling <github@dartcafe.de>
 *
 * @author René Gieling <github@dartcafe.de>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Polls\Event;

use OCA\Polls\Notification\Notifier;
use OCA\Polls\Db\Poll;

class PollTakeoverEvent extends PollOwnerChangeEvent {
	public function __construct(
		Poll $poll
	) {
		parent::__construct($poll);
	}

	public function getNotification(): array {
		return [
			'msgId' => Notifier::NOTIFY_POLL_TAKEOVER,
			'objectType' => 'poll',
			'objectValue' => $this->getPollId(),
			'recipient' => $this->getPollOwner(),
			'actor' => $this->getActor(),
			'pollTitle' => $this->getPollTitle(),
		];
	}
}
