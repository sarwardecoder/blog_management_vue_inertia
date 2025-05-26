<script setup>
import NavBar from "../Pages/NavBar.vue";
import { reactive, ref } from "vue";
import { Link, useForm, router } from "@inertiajs/vue3";
import PublicPost from "../Pages/Post/PublicPost.vue"

import axios from "axios";

const comments = ref([]); // A reactive list of comments

// Fetch comments from API (assuming you need this)
const fetchComments = async () => {
    try {
        const response = await axios.get("/api/comments"); // Change the URL if necessary
        comments.value = response.data.comments;
    } catch (error) {
        console.error("Error fetching comments:", error);
    }
};

// Delete a comment
const deleteComment = async (commentId) => {
    try {
        const response = await axios.delete(`/comments/${commentId}`);
        comments.value = comments.value.filter(
            (comment) => comment.id !== commentId
        ); // Remove comment from UI
        alert(response.data.success);
    } catch (error) {
        alert(error.response.data.error); // Display error message
    }
};

// Edit a comment
const editComment = async (commentId) => {
    const newBody = prompt("Enter new comment body:");
    if (newBody) {
        try {
            const response = await axios.put(`/comments/${commentId}`, {
                body: newBody,
            });
            const comment = comments.value.find(
                (comment) => comment.id === commentId
            );
            if (comment) {
                comment.body = newBody; // Update the comment in the list
            }
            alert(response.data.success);
        } catch (error) {
            alert(error.response.data.error);
        }
    }
};

const props = defineProps({
    posts: Object,
    LoggedUser: Object,
    like: Boolean,
    likeCount: Number,
});

const form = useForm({});

const logout = () => {
    form.post("/user/logout", {
        forceFormData: true,
        preserveScroll: true,
    });
};

const handleSubmit = () => {
    form.post("/post/store", {
        forceFormData: true,
        preserveScroll: true,
    });
};
const isLiked = ref(props.like);
const count = ref(props.likeCount);

const handleLike = async (postId) => {
    await form.post(`/post/${postId}/like`, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            // Toggle like state
            isLiked.value = !isLiked.value;

            // Update like count accordingly
            if (isLiked.value) {
                count.value++;
            } else {
                count.value--;
            }
        },
    });
};

const handleBookmark =  (postId) => {
    form.post(`/posts/${postId}/bookmarks`, {
        preserveScroll: true,
        onSuccess: () => {
            // Handle bookmark
        },
    });
};
const searchQuery = ref("");
const commentBody = reactive({}); //no problem for thousand posts too
const handleCommentSubmit = (postId) => {
    const localForm = useForm({
        body: commentBody[postId],
    });

    localForm.post(`/user/comments/${postId}`, {
        preserveScroll: true,
        onSuccess: ({ props }) => {
            const newComment = localForm.data().body;

            // Find post
            const post = props.posts.data.find((p) => p.id === postId);
            if (post) {
                // Push the new comment into the post.comments
                post.comments.push({
                    id: Date.now(), // temp ID
                    body: newComment,
                    user: props.LoggedUser,
                });
            }

            commentBody[postId] = "";
        },
    });
};

const handlePostEdit = async (postId) => {
    form.get(
        `/user/dashboard/post/edit/${postId}`,
        {
            body: commentBody.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                commentBody.value = "";
            },
        }
    );
};
const handlePostDelete = async (postId) => {
    form.delete(
        `/user/dashboard/post/delete/${postId}`,
        {
            body: commentBody.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                commentBody.value = "";
            },
        }
    );
};

const handleSearch = () => {
    form.get("/posts", {
        search: searchQuery.value,
    });
};
</script>
<template>
    <div class="container">
        <NavBar :LoggedUser="LoggedUser"/>

        <public-post :posts="posts" :LoggedUser="LoggedUser" />
        
    </div>
</template>