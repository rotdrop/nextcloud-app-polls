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
	<NcCheckboxRadioSwitch :checked.sync="anonymous" type="switch">
		{{ t('polls', 'Anonymous poll') }}
	</NcCheckboxRadioSwitch>
</template>

<script>
import { mapState } from 'vuex'
import { NcCheckboxRadioSwitch } from '@nextcloud/vue'

export default {
	name: 'ConfigAnonymous',

	components: {
		NcCheckboxRadioSwitch,
	},

	computed: {
		...mapState({
			poll: (state) => state.poll,
		}),

		anonymous: {
			get() {
				return !!this.poll.anonymous
			},
			set(value) {
				this.$store.commit('poll/setProperty', { anonymous: +value })
				this.$emit('change')
			},
		},

	},
}
</script>
