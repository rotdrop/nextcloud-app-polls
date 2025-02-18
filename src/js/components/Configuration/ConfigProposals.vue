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
		<NcCheckboxRadioSwitch :checked.sync="allowProposals" type="switch">
			{{ t('polls', 'Allow Proposals') }}
		</NcCheckboxRadioSwitch>

		<NcCheckboxRadioSwitch v-show="proposalsAllowed" :checked.sync="proposalExpiration" type="switch">
			{{ t('polls', 'Proposal closing date') }}
		</NcCheckboxRadioSwitch>

		<NcDatetimePicker v-show="proposalExpiration && proposalsAllowed" v-model="pollExpire" v-bind="expirationDatePicker" />
	</div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import moment from '@nextcloud/moment'
import { NcCheckboxRadioSwitch, NcDatetimePicker } from '@nextcloud/vue'
import { writePoll } from '../../mixins/writePoll.js'

export default {
	name: 'ConfigProposals',

	components: {
		NcCheckboxRadioSwitch,
		NcDatetimePicker,
	},

	mixins: [writePoll],

	computed: {
		...mapState({
			poll: (state) => state.poll,
		}),

		...mapGetters({
			proposalsAllowed: 'poll/proposalsAllowed',
			proposalsOptions: 'poll/proposalsOptions',
		}),

		// Add bindings
		allowProposals: {
			get() {
				return (this.poll.allowProposals === 'allow')
			},
			set(value) {
				this.writeValue({ allowProposals: value ? 'allow' : 'disallow' })
			},
		},

		pollExpire: {
			get() {
				return moment.unix(this.poll.proposalsExpire)._d
			},
			set(value) {
				this.writeValue({ proposalsExpire: moment(value).unix() })
			},
		},

		proposalExpiration: {
			get() {
				return !!this.poll.proposalsExpire
			},
			set(value) {
				if (value) {
					this.writeValue({ proposalsExpire: moment().add(1, 'week').unix() })
				} else {
					this.writeValue({ proposalsExpire: 0 })
				}
			},
		},

		firstDOW() {
			// vue2-datepicker needs 7 for sunday
			if (moment.localeData()._week.dow === 0) {
				return 7
			}
			return moment.localeData()._week.dow

		},

		expirationDatePicker() {
			return {
				editable: true,
				minuteStep: 5,
				type: 'datetime',
				format: moment.localeData().longDateFormat('L LT'),
				placeholder: t('polls', 'Proposals possible until'),
				confirm: true,
				lang: {
					formatLocale: {
						firstDayOfWeek: this.firstDOW,
						months: moment.months(),
						monthsShort: moment.monthsShort(),
						weekdays: moment.weekdays(),
						weekdaysMin: moment.weekdaysMin(),
					},
				},
			}
		},
	},

	methods: {
		writeValue(e) {
			this.$store.commit('poll/setProperty', e)
			this.writePoll() // from mixin
		},
	},
}
</script>
