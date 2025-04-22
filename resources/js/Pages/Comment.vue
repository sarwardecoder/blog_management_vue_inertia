<script setup>
import { usePage, Link } from '@inertiajs/vue3';
defineProps({
  comment: Object,
  post: Object,
  isReply: Boolean,
  loggedUserId: Number
})
</script>

<template>
  <div :class="['comment mt-3', isReply ? 'nested-comment' : '']">
    <strong>{{ comment.user?.name ?? 'Anonymous' }}</strong> said:
    <p>{{ comment.body }}</p>
    <small>{{ comment.created_at }}</small> <!-- Format on server or use a library -->

    <form
      v-if="post.user_id === loggedUserId"
      method="POST"
      class="d-inline comment-delete-form"
      :data-comment-id="comment.id"
    >
      <button type="submit" class="btn btn-sm btn-link text-danger p-0 ml-2">
        Delete
      </button>
    </form>

    <Link
      href="#"
      class="reply-toggle text-primary d-block mt-2"
      style="font-size: 0.9rem;"
    >
      Reply
    </Link>

    <!-- Reply Form -->
    <form
      :action="`/comments/${post.id}`"
      method="POST"
      class="reply-form ajax-reply-form mt-2"
      style="display: none;"
    >
      <div class="form-group">
        <textarea
          name="body"
          class="form-control"
          rows="2"
          placeholder="Write a reply..."
        ></textarea>
        <div class="text-danger small error-msg mt-1" style="display: none;"></div>
      </div>
      <input type="hidden" name="parent_id" :value="comment.id" />
      <button type="submit" class="btn btn-sm btn-secondary">Post Reply</button>
    </form>

    <!-- Recursive: render replies -->
    <div v-if="comment.replies?.length">
      <Comment
        v-for="reply in comment.replies"
        :key="reply.id"
        :comment="reply"
        :post="post"
        :isReply="true"
        :loggedUserId="loggedUserId"
      />
    </div>
  </div>
</template>

<style scoped>
.nested-comment {
  margin-left: 30px;
}
</style>
