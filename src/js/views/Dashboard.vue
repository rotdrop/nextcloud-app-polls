<!--
  - @copyright Copyright (c) 2022 Michael Longo <contact@tiller.fr>
  -
  - @author Michael Longo <contact@tiller.fr>
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
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program. If not, see <http://www.gnu.org/licenses/>.
  -
  -->

<template>
	<div>
		<NcDashboardWidget :items="relevantPolls"
			:empty-content-message="t('polls', 'No polls found for this category')"
			:show-more-text="t('polls', 'Relevant polls')"
			:loading="loading"
			@hide="() => {}"
			@markDone="() => {}">
			<template #emptyContentIcon>
				<PollsAppIcon />
			</template>

			<template #default="{ item }">
				<a :href="pollLink(item)">
					<div class="poll-item__item">
						<div class="item__icon-spacer">
							<TextPollIcon v-if="item.type === 'textPoll'" />
							<DatePollIcon v-else />
						</div>

						<div class="item__title">
							<div class="item__title__title">
								{{ item.title }}
							</div>

							<div class="item__title__description">
								{{ item.description ? item.description : t('polls', 'No description provided') }}
							</div>
						</div>
					</div>
				</a>
			</template>
		</NcDashboardWidget>
	</div>
</template>

<script>
import { NcDashboardWidget } from '@nextcloud/vue'
import { showError } from '@nextcloud/dialogs'
import TextPollIcon from 'vue-material-design-icons/FormatListBulletedSquare.vue'
import DatePollIcon from 'vue-material-design-icons/CalendarBlank.vue'
import PollsAppIcon from '../components/AppIcons/PollsAppIcon.vue'
import { mapGetters } from 'vuex'
import { generateUrl } from '@nextcloud/router'

export default {
	name: 'Dashboard',
	components: {
		NcDashboardWidget,
		DatePollIcon,
		PollsAppIcon,
		TextPollIcon,
	},

	data() {
		return {
			loading: false,
		}
	},

	computed: {
		...mapGetters({
			filteredPolls: 'polls/filtered',
		}),

		relevantPolls() {
			const list = [
				...this.filteredPolls('relevant'),
			]
			return list.slice(0, 6)
		},

		pollLink() {
			return (poll) => generateUrl(`/apps/polls/vote/${poll.id}`)
		},
	},

	beforeMount() {
		this.loading = true
		this.$store.dispatch('polls/list').then(() => {
			this.loading = false
			return null
		}).catch(() => {
			showError(t('polls', 'Error loading poll list'))
		})
	},
}

</script>

<style lang="scss" scoped>
	.poll-item__item {
		display: flex;
		padding: 4px 0;

		&.active {
			background-color: var(--color-primary-light);
		}

		&:hover {
			background-color: var(--color-background-hover);
		}
	}

	.item__title {
		display: flex;
		flex-direction: column;
		overflow: hidden;

		*  {
			display: block;
			overflow: hidden;
			white-space: nowrap;
			text-overflow: ellipsis;
		}

		.item__title__description {
			opacity: 0.5;
		}
	}

	.item__icon-spacer {
		width: 44px;
		min-width: 44px;
	}
</style>
