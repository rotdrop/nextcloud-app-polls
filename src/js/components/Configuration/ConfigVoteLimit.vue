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
	<div>
		<NcCheckboxRadioSwitch :checked.sync="useVoteLimit" type="switch">
			{{ t('polls', 'Limit "Yes" votes per user') }}
		</NcCheckboxRadioSwitch>

		<InputDiv v-if="voteLimit"
			v-model="voteLimit"
			class="indented"
			type="number"
			inputmode="numeric"
			use-num-modifiers />
	</div>
</template>

<script>
import { mapState } from 'vuex'
import { NcCheckboxRadioSwitch } from '@nextcloud/vue'
import InputDiv from '../Base/InputDiv.vue'

export default {
	name: 'ConfigVoteLimit',

	components: {
		NcCheckboxRadioSwitch,
		InputDiv,
	},

	computed: {
		...mapState({
			poll: (state) => state.poll,
			countOptions: (state) => state.options.list.length,
		}),

		useVoteLimit: {
			get() {
				return (this.poll.voteLimit !== 0)
			},
			set(value) {
				this.$store.commit('poll/setProperty', { voteLimit: value ? 1 : 0 })
				this.$emit('change')
			},
		},

		voteLimit: {
			get() {
				return this.poll.voteLimit
			},
			set(value) {
				if (!this.useVoteLimit) {
					value = 0
				} else if (value < 1) {
					value = this.countOptions
				} else if (value > this.countOptions) {
					value = 1
				}
				this.$store.commit('poll/setProperty', { voteLimit: value })
				this.$emit('change')
			},
		},
	},
}
</script>
