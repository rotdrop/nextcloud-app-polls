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
	<div class="vote-item" :class="[answer, { confirmed: confirmed }, { active: isVotable }, {currentuser: isCurrentUser}]">
		<div v-if="isActive" class="icon" @click="setVote()" />
		<div v-else class="icon" />
		<slot name="indicator" />
	</div>
</template>

<script>

import { mapGetters, mapState } from 'vuex'
import { showSuccess, showError } from '@nextcloud/dialogs'
export default {
	name: 'VoteItem',

	props: {
		option: {
			type: Object,
			default: undefined,
		},
		userId: {
			type: String,
			default: '',
		},
		locked: {
			type: Boolean,
			default: false,
		},
		confirmed: {
			type: Boolean,
			default: false,
		},
	},

	computed: {
		...mapState({
			currentUser: (state) => state.poll.acl.userId,
			allowVote: (state) => state.poll.acl.allowVote,
		}),

		...mapGetters({
			closed: 'poll/isClosed',
			answerSequence: 'poll/answerSequence',
		}),

		isVotable() {
			return this.isActive
				&& this.isValidUser
				&& !this.closed
				&& !this.locked
		},

		isActive() {
			return this.isCurrentUser && this.allowVote
		},

		isCurrentUser() {
			return this.currentUser === this.userId
		},

		answer() {
			return this.$store.getters['votes/getVote']({
				option: this.option,
				userId: this.userId,
			}).answer
		},

		nextAnswer() {
			if (this.answerSequence.indexOf(this.answer) < 0) {
				return this.answerSequence[1]
			}
			return this.answerSequence[(this.answerSequence.indexOf(this.answer) + 1) % this.answerSequence.length]

		},

		isValidUser() {
			return (this.userId !== '' && this.userId !== null)
		},

	},

	methods: {
		async setVote() {
			try {
				await this.$store.dispatch('votes/set', {
					option: this.option,
					userId: this.userId,
					setTo: this.nextAnswer,
				})
				showSuccess(t('polls', 'Vote saved'), { timeout: 2000 })
			} catch (e) {
				showError(t('polls', 'Error saving vote'))

			}
		},
	},
}

</script>

<style lang="scss">

.vote-item {
	display: flex;
	background-color: var(--color-polls-background-no);
	transition: all 0.4s ease-in-out;
	> .icon {
		color: var(--color-polls-foreground-no);
		background-position: center;
		background-repeat: no-repeat;
		background-size: 90%;
		width: 30px;
		height: 30px;
		flex: 0 0 auto;
		transition: all 0.4s ease-in-out;
	}

	&.yes {
		background-color: var(--color-polls-background-yes);
		> .icon {
			color: var(--color-polls-foreground-yes);
			background-image: var(--icon-polls-yes)
		}
	}

	&.maybe {
		background-color: var(--color-polls-background-maybe);
		> .icon {
			color: var(--color-polls-foreground-maybe);
			background-image: var(--icon-polls-maybe)
		}
	}

	&.active, &.active.no {
		background-color: transparent;
		> .icon {
			cursor: pointer;
			border: 2px solid;
			border-radius: var(--border-radius);
		}
	}

	&.no {
		background-color: var(--color-polls-background-no);
		&.active > .icon {
			color: var(--color-polls-foreground-no);
			background-image: var(--icon-polls-no)
		}
	}

	&.active > .icon:hover {
		background-size: 100%;
		width: 35px;
		height: 35px;
	}

}

.vote-item.confirmed {
	background-color: transparent;
	&:not(.yes):not(.maybe) .icon {
		background-image: var(--icon-polls-no);
	}
}

</style>
