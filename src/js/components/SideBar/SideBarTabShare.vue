<!--
  - @copyright Copyright (c) 2018 René Gieling <github@dartcafe.de>
  -
  - @author René Gieling <github@dartcafe.de>
  -
  - @license GNU AGPL version 3 or any later version
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program.  If not, see <http://www.gnu.org/licenses/>.
  -
  -->

<template>
	<div class="sidebar-share">
		<SharesListUnsent class="shares unsent" />
		<SharesList class="shares effective" />
	</div>
</template>

<script>
import { mapState } from 'vuex'
import SharesList from '../Shares/SharesList.vue'
import SharesListUnsent from '../Shares/SharesListUnsent.vue'

export default {
	name: 'SideBarTabShare',

	components: {
		SharesList,
		SharesListUnsent,
	},

	computed: {
		...mapState({
			pollId: (state) => state.poll.id,
			pollTitle: (state) => state.poll.title,
		}),

	},
}
</script>

<style lang="scss">
	.sidebar-share {
		display: flex;
		flex-direction: column;
	}

	.shares-list {
		display: flex;
		flex-flow: column;
		justify-content: flex-start;
		padding-top: 8px;

		> li {
			display: flex;
			align-items: stretch;
			margin: 4px 0;
		}
	}

	.share-item {
		display: flex;
		flex: 1;
		align-items: center;
		max-width: 100%;
	}

	.share-item__description {
		flex: 1;
		min-width: 50px;
		color: var(--color-text-maxcontrast);
		padding-left: 8px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
</style>
