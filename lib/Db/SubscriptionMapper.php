<?php
/**
 * @copyright Copyright (c) 2017 Vinzenz Rosenkranz <vinzenz.rosenkranz@gmail.com>
 *
 * @author Vinzenz Rosenkranz <vinzenz.rosenkranz@gmail.com>
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

namespace OCA\Polls\Db;

use OCP\AppFramework\Db\QBMapper;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;
use OCP\Migration\IOutput;

/**
 * @template-extends QBMapper<Subscription>
 */
class SubscriptionMapper extends QBMapper {
	public const TABLE = Subscription::TABLE;

	public function __construct(IDBConnection $db) {
		parent::__construct($db, Subscription::TABLE, Subscription::class);
	}

	/**
	 * @throws \OCP\AppFramework\Db\DoesNotExistException if not found
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException if more than one result
	 * @return Subscription[]
	 * @psalm-return array<array-key, Subscription>
	 */
	public function findAll(): array {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			 ->from($this->getTableName());

		return $this->findEntities($qb);
	}

	/**
	 * @throws \OCP\AppFramework\Db\DoesNotExistException if not found
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException if more than one result
	 * @return Subscription[]
	 * @psalm-return array<array-key, Subscription>
	 */
	public function findAllByPoll(int $pollId): array {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			 ->from($this->getTableName())
			 ->where(
			 	$qb->expr()->eq('poll_id', $qb->createNamedParameter($pollId, IQueryBuilder::PARAM_INT))
			 );

		return $this->findEntities($qb);
	}

	/**
	 * @throws \OCP\AppFramework\Db\DoesNotExistException if not found
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException if more than one result
	 */
	public function findByPollAndUser(int $pollId, string $userId): Subscription {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('poll_id', $qb->createNamedParameter($pollId, IQueryBuilder::PARAM_INT))
			)
			->andWhere(
				$qb->expr()->eq('user_id', $qb->createNamedParameter($userId, IQueryBuilder::PARAM_STR))
			);

		return $this->findEntity($qb);
	}

	public function unsubscribe(int $pollId, string $userId): void {
		$qb = $this->db->getQueryBuilder();

		$qb->delete($this->getTableName())
		->where(
			$qb->expr()->eq('poll_id', $qb->createNamedParameter($pollId, IQueryBuilder::PARAM_INT))
		)
		->andWhere(
			$qb->expr()->eq('user_id', $qb->createNamedParameter($userId, IQueryBuilder::PARAM_STR))
		);

		$qb->executeStatement();
	}

	public function removeDuplicates(?IOutput $output = null): int {
		$count = 0;
		try {
			// remove duplicates from polls_share
			// preserve the first entry
			$query = $this->db->getQueryBuilder();
			$query->select('id', 'poll_id', 'user_id')
				->from($this->getTableName());
			$foundEntries = $query->executeQuery();

			$delete = $this->db->getQueryBuilder();
			$delete->delete($this->getTableName())->where('id = :id');

			$entries2Keep = [];

			while ($row = $foundEntries->fetch()) {
				$currentRecord = [
					$row['poll_id'],
					$row['user_id']
				];

				if (in_array($currentRecord, $entries2Keep)) {
					$delete->setParameter('id', $row['id']);
					$delete->executeStatement();
					$count++;
				} else {
					$entries2Keep[] = $currentRecord;
				}
			}
		} catch (Exception $e) {
			if ($e->getReason() === Exception::REASON_DATABASE_OBJECT_NOT_FOUND) {
				// ignore silently
			}
			throw $e;
		}

		if ($output && $count) {
			$output->info('Removed ' . $count . ' duplicate records from ' . $this->getTableName());
		}

		return $count;
	}

	public function deleteByUserId(string $userId): void {
		$query = $this->db->getQueryBuilder();
		$query->delete($this->getTableName())
			->where('user_id = :userId')
			->setParameter('userId', $userId);
		$query->executeStatement();
	}
}
