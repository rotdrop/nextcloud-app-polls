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
	<div :class="['comment-item' , {currentuser: isCurrentUser}]">
		<UserItem v-bind="comment.user" hide-names />
		<div class="comment-item__content">
			<span class="comment-item__user">{{ comment.user.displayName }}</span>
			<span class="comment-item__date">{{ dateCommentedRelative }}</span>
			<div v-for="(subComment) in comment.subComments"
				:key="subComment.id"
				class="comment-item__subcomment">
				<!-- eslint-disable vue/no-v-html -->
				<span class="comment-item__comment"
					v-html="linkify(subComment.comment)" />
				<!-- eslint-enable vue/no-v-html -->

				<ActionDelete v-if="comment.user.userId === acl.userId || acl.isOwner"
					:title="t('polls', 'Delete comment')"
					@delete="deleteComment(subComment)" />
			</div>
		</div>
	</div>
</template>

<script>
import moment from '@nextcloud/moment'
import linkifyStr from 'linkify-string'
import { showError } from '@nextcloud/dialogs'
import { mapState } from 'vuex'
import ActionDelete from '../Actions/ActionDelete.vue'

export default {
	name: 'CommentItem',
	components: {
		ActionDelete,
	},

	props: {
		comment: {
			type: Object,
			default: null,
		},
	},

	computed: {
		...mapState({
			acl: (state) => state.poll.acl,
			currentUser: (state) => state.poll.acl.userId,
		}),

		dateCommentedRelative() {
			return moment.unix(this.comment.timestamp).fromNow()
		},

		isCurrentUser() {
			return this.currentUser === this.comment.user.userId
		},
	},

	methods: {
		linkify(comment) {
			return linkifyStr(comment)
		},

		async deleteComment(comment) {
			try {
				await this.$store.dispatch({ type: 'comments/delete', comment })
			} catch {
				showError(t('polls', 'Error while deleting the comment'))
			}
		},
	},
}
</script>

<style lang="scss">
	.comment-item {
		display: flex;
		align-items: start;
		margin-bottom: 24px;
	}

	.comment-item__user {
		font-weight: 600;
		font-size: 0.9em;
	}

	.comment-item__date {
		opacity: 0.5;
		font-size: 0.8em;
		text-align: right;
		&::before {
			content: ' ~ '
		}
	}

	.comment-item__content {
		margin-left: 8px;
		flex: 1 1;
		padding-top: 2px;

		.material-design-icon {
			visibility: hidden;
		}

		.comment-item__subcomment {
			display: flex;
			align-items: center;

			&:hover {
				background: var(--color-background-hover);
				.material-design-icon {
					visibility: visible;
				}
			}
		}

		.comment-item__comment {
			hyphens: auto;
			flex: 1;
			a {
				text-decoration-line: underline;
			}
		}
	}

	// experimental
	.alternativestyle {
		.comment-item {
		flex-direction: row-reverse;
			&.currentuser {
				flex-direction: row;
			}
		}

		.comment-item__content {
			border: solid 1px var(--color-primary-element-light);
			border-radius: var(--border-radius-large);
			background-color: var(--color-primary-light);
			box-shadow: 2px 2px 6px var(--color-box-shadow);
			padding-left: 8px;
			padding-bottom: 10px;

			.comment-item__subcomment {
				margin-right: 4px;

				&:hover {
					background: var(--color-primary-hover);
					color: var(--color-primary-light-hover);
					margin-left: -4px;
					padding-left: 4px;
					border-radius: var(--border-radius-large);
				}
			}
		}
	}

</style>
