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
	<div class="comments">
		<CommentAdd v-if="acl.allowComment" />
		<Comments v-if="!showEmptyContent" />
		<NcEmptyContent v-else :title="t('polls', 'No comments')">
			<template #icon>
				<CommentsIcon />
			</template>
			<template #action>
				{{ t('polls', 'Be the first.') }}
			</template>
		</NcEmptyContent>
	</div>
</template>

<script>
import CommentAdd from '../Comments/CommentAdd.vue'
import Comments from '../Comments/Comments.vue'
import { NcEmptyContent } from '@nextcloud/vue'
import { mapGetters, mapState } from 'vuex'
import CommentsIcon from 'vue-material-design-icons/CommentProcessing.vue'

export default {
	name: 'SideBarTabComments',
	components: {
		CommentAdd,
		Comments,
		NcEmptyContent,
		CommentsIcon,
	},

	computed: {
		...mapState({
			acl: (state) => state.poll.acl,
		}),

		...mapGetters({
			countComments: 'comments/count',
		}),

		showEmptyContent() {
			return this.countComments === 0
		},

	},

}
</script>
