<!--
  - @copyright Copyright (c) 2021 René Gieling <github@dartcafe.de>
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
	<div class="action sort-options">
		<NcButton v-tooltip="caption"
			type="tertiary"
			@click="clickAction()">
			<template #icon>
				<SortByDateOptionIcon v-if="isRanked && pollType === 'datePoll'" />
				<SortByOriginalOrderIcon v-else-if="isRanked && pollType === 'textPoll'" />
				<SortByRankIcon v-else />
			</template>
		</NcButton>
	</div>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
import { NcButton } from '@nextcloud/vue'
import SortByOriginalOrderIcon from 'vue-material-design-icons/FormatListBulletedSquare.vue'
import SortByRankIcon from 'vue-material-design-icons/FormatListNumbered.vue'
import SortByDateOptionIcon from 'vue-material-design-icons/SortClockAscendingOutline.vue'

export default {
	name: 'ActionSortOptions',

	components: {
		SortByRankIcon,
		SortByOriginalOrderIcon,
		SortByDateOptionIcon,
		NcButton,
	},

	computed: {
		...mapState({
			isRanked: (state) => state.options.ranked,
			pollType: (state) => state.poll.type,
		}),

		caption() {
			if (this.isRanked && this.pollType === 'datePoll') {
				return t('polls', 'Date order')
			}

			if (this.isRanked && this.pollType === 'textPoll') {
				return t('polls', 'Original order')
			}

			return t('polls', 'Ranked order')
		},
	},

	methods: {
		...mapMutations({
			clickAction: 'options/setRankOrder',
		}),
	},
}
</script>
