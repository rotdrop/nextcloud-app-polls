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
	<div>
		<div v-if="proposalsExist">
			{{ t('polls', 'Shifting dates is disabled to prevent shifting of other user\'s proposals.') }}
		</div>
		<div v-else class="select-unit">
			<InputDiv v-model="shift.step" use-num-modifiers />
			<NcMultiselect v-model="shift.unit"
				:options="dateUnits"
				label="name"
				track-by="value" />
			<NcButton class="submit"
				type="tertiary"
				@click="shiftDates(shift)">
				<template #icon>
					<SubmitIcon />
				</template>
			</NcButton>
		</div>
	</div>
</template>

<script>

import { mapState, mapGetters } from 'vuex'
import InputDiv from '../Base/InputDiv.vue'
import { NcButton, NcMultiselect } from '@nextcloud/vue'
import { dateUnits } from '../../mixins/dateMixins.js'
import SubmitIcon from 'vue-material-design-icons/ArrowRight.vue'

export default {
	name: 'OptionsDateShift',

	components: {
		SubmitIcon,
		InputDiv,
		NcMultiselect,
		NcButton,
	},

	mixins: [dateUnits],

	data() {
		return {
			shift: {
				step: 1,
				unit: { name: t('polls', 'Week'), value: 'week' },
			},
		}
	},

	computed: {
		...mapState({
			options: (state) => state.options.list,
		}),

		...mapGetters({
			proposalsExist: 'options/proposalsExist',
		}),
	},

	methods: {
		shiftDates(payload) {
			this.$store.dispatch('options/shift', { shift: payload })
		},
	},
}

</script>

<style lang="scss">
.select-unit {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	.multiselect {
		margin: 0 8px;
		width: unset !important;
		min-width: 75px;
		flex: 1;
	}

	.button-vue.button-vue--vue-tertiary {
		padding: 0;
		min-width: 0;
	}

	.button-vue__text,
	.button-vue--text-only .button-vue__text {
		margin: 0;
	}
}
</style>
