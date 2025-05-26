<script setup>
import NavBar from "../NavBar.vue";
import { reactive, ref, onMounted } from "vue";
import { Link, useForm, router } from "@inertiajs/vue3";
import ThreeDotComponent from "../../Components/ThreeDotComponent.vue";
import { useLikes } from '../../composables/useLikes'


import axios from "axios";

const postId = ref() // You can get this dynamically
const { likeCount, isLiked, fetchLikes, toggleLike, loading } = useLikes(postId)


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

// const handleSubmit = () => {
//     form.post("/post/store", {
//         forceFormData: true,
//         preserveScroll: true,
//     });
// };
// const isLiked = ref(props.like);
// const count = ref(props.likeCount);

// const handleLike = async (postId) => {
//     await form.post(`/post/${postId}/like`, {
//         preserveScroll: true,
//         preserveState: true,
//         onSuccess: () => {
//             // Toggle like state
//             isLiked.value = !isLiked.value;

//             // Update like count accordingly
//             if (isLiked.value) {
//                 count.value++;
//             } else {
//                 count.value--;
//             }
//         },
//     });
// };
const fetchLikes2 = async () => {
    loading.value = true
    error.value = null

    try {
        const response = await axios.get(`/posts/${postId}/likes`)

        likeCount.value = response.data.like_count
        isLiked.value = response.data.is_liked_by_user

    } catch (err) {
        error.value = err.response?.data?.message || 'Error fetching likes'
    } finally {
        loading.value = false
    }
}


//composables 


onMounted(() => {
    fetchLikes2()
})

const handleBookmark = (postId) => {
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
        <!--  NavBar -->

        <NavBar :LoggedUser="LoggedUser" />

        <!--  NavBar Ends here-->

        <!-- user posts here  -->
        <!-- create new post -->

        <button class="btn btn-primary btn-md">
            <Link class="nav-link active" href="/user/dashboard/post/create">Create New Post
            </Link>
        </button>


        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4 capitalize">
                    {{ LoggedUser.name }}'s Blog Site
                </h1>

                <h6 class="border-bottom pb-2 mb-4">Recent updates</h6>

                <!-- Search Form -->
                <form @submit.prevent="handleSearch" class="mb-4">
                    <div class="input-group">
                        <input type="text" v-model="searchQuery" class="form-control" placeholder="Search posts..." />
                        <button class="btn btn-outline-primary" type="submit">
                            Search
                        </button>
                    </div>
                </form>

                <!-- Posts Grid -->
                <div class="row g-4">
                    <div class="col-md-4" v-for="post in props.posts.data" :key="post.id">
                        <div class="card h-100">
                            <img v-if="post.img" :src="`/${post.img}`" class="card-img-top" alt="Post Image"
                                style="height: 200px; object-fit: cover" />

                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0">
                                        {{ post.title }}
                                    </h5>
                                    <small class="text-muted">
                                        {{
                                            new Date(
                                                post.created_at
                                            ).toLocaleDateString()
                                        }}
                                    </small>
                                </div>

                                <h6 class="text-muted">
                                    By: {{ post.user?.name || "Unknown" }}
                                </h6>
                                <p class="card-text">{{ post.content }}</p>

                                <!-- Tags -->
                                <div v-if="post.tags?.length" class="mb-3">
                                    <span v-for="tag in post.tags" :key="tag.id" class="badge bg-info me-1">
                                        {{ tag.name }}
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 mb-3">
                                    <!-- <button
    @click="handleLike(post.id)"
    class="btn btn-sm"
    :class="isLiked ? 'btn-danger' : 'btn-outline-danger'"
>
    <i class="bi bi-heart-fill"></i>
    {{ count||0 }}
</button> -->
                                    <div>
                                        <button @click="toggleLike" :disabled="loading">
                                            {{ isLiked ? '❤️ Unlike' : '🤍 Like' }}
                                        </button>
                                        <span>{{ likeCount }} likes</span>
                                    </div>

                                    <button @click="handleBookmark(post.id)" class="btn btn-sm" :class="post.bookmarked
                                            ? 'btn-primary'
                                            : 'btn-outline-primary'
                                        ">
                                        <i :class="props.like
                                                ? 'bi bi-heart-fill'
                                                : 'bi bi-heart'
                                            "></i>
                                    </button>
                                </div>

                                <!-- Comment Form -->
                                <div class="mb-3">
                                    <textarea v-model="commentBody[post.id]" class="form-control mb-2" rows="2"
                                        placeholder="Write a comment..."></textarea>
                                    <button @click="handleCommentSubmit(post.id)" class="btn btn-primary btn-sm">
                                        Post Comment
                                    </button>
                                    <button @click="handlePostEdit(post.id)" class="btn btn-warning btn-sm m-2">
                                        Post Edit
                                    </button>
                                    <button @click="handlePostDelete(post.id)" class="btn btn-danger btn-sm">
                                        Post Delete
                                    </button>
                                </div>

                                <!-- Comments List -->
                                <div class="mt-3 comment">
                                    <div v-for="comment in post.comments" :key="comment.id"
                                        class="border-bottom pb-2 mb-2">
                                        <p class="mb-1">{{ comment.body }}</p>
                                        <small class="text-muted">
                                            Commented by
                                            {{
                                                comment.user?.name || "Unknown"
                                            }}
                                        </small>

                                        <!-- toggle dropdown buttons for comment  -->
                                        <div class="d-flex end-0">
                                            <li class="nav-link dropdown-toggle" href="#" role="button"
                                                data-bs-toggle="dropdown">
                                                <div class="header-info"></div>
                                            </li>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <button class="btn btn-warning btn-sm m-1" @click="
                                                        editComment(
                                                            comment.id
                                                        )
                                                        ">
                                                        Comment Edit
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="btn btn-danger btn-sm" @click="
                                                        deleteComment(
                                                            comment.id
                                                        )
                                                        ">
                                                        Comment Delete
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <ThreeDotComponent />

                <!-- Posts Grid Ends here -->
            </div>
        </div>

    </div>
</template>
<style scoped></style>
