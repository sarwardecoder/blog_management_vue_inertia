<script setup>
import { ref } from "vue";
import { Link, useForm, router } from '@inertiajs/vue3'
import PublicPost from "../Post/PublicPost.vue";

const props = defineProps({
    posts: Array,
    loggedUser: Object,
});
const logout = ()=>{
    form.post("/user/logout", {
        forceFormData: true,
        preserveScroll: true,
    });
}

const handleSubmit = () => {
    form.post("/post/store", {
        forceFormData: true,
        preserveScroll: true,
    });
};

const form = useForm({});
const searchQuery = ref("");
const commentBody = ref("");

const handleLike = async (postId) => {
    form.post(`/posts/${postId}/likes`, {
        preserveScroll: true,
        onSuccess: () => {
            // Handle success if needed
        },
    });
};

const handleBookmark = async (postId) => {
    form.post(`/posts/${postId}/bookmarks`, {
        preserveScroll: true,
        onSuccess: () => {
            // Handle success if needed
        },
    });
};
const handleCommentSubmit = async (postId) => {
    form.post(
        `/user/comments/${postId}`,
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
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <Link class="navbar-brand" href="#">Navbar</Link>
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div
                    class="collapse navbar-collapse"
                    id="navbarSupportedContent"
                >
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item active">
                            <Link class="nav-link active" href="/user/dashboard"
                                >My Dashboard</Link
                            >
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link active" href="/user/dashall"
                                >All Posts</Link
                            >
                        </li>
                    </ul>
                    <div class="d-flex">
                        <Link
                            class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                        >
                            <!-- <img :src="LoggedUser.img ? '/' + LoggedUser.img : '/uploads/default.jpg'" width="30" height="30" class="rounded-circle" /> -->
                            <div class="header-info">
                                <!-- <span>{{ LoggedUser.name }}</span> -->
                            </div>
                        </Link>
                        <ul class="dropdown-menu dropdown-menu-end">
                            
                            <li>
                                <button
                                    @click="logout()"
                                    class="dropdown-item text-danger"
                                >
                                    Logout
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- user posts here  -->
        <!-- create new post -->

        <button class="btn btn-primary btn-md">
            <Link class="nav-link active" href="/user/dashboard/post/create"
                >Create New Post
                </Link>
        </button>

        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="container-fluid">
                <Link class="navbar-brand" href="/post/public">Blog Site</Link>
            </div>
        </nav>

        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4"> Blog Site</h1>
                <h6 class="border-bottom pb-2 mb-4">Recent updates</h6>

                <!-- Search Form -->
                <form @submit.prevent="handleSearch" class="mb-4">
                    <div class="input-group">
                        <input
                            type="text"
                            v-model="searchQuery"
                            class="form-control"
                            placeholder="Search posts..."
                        />
                        <button class="btn btn-outline-primary" type="submit">
                            Search
                        </button>
                    </div>
                </form>

                <!-- Posts Grid -->
                <div class="row g-4">
                    <div
                        class="col-md-4"
                        v-for="post in props.posts.data"
                        :key="post.id"
                    >
                        <div class="card h-100">
                            <img
                                v-if="post.img"
                                :src="`/storage/${post.img}`"
                                class="card-img-top"
                                alt="Post Image"
                                style="height: 200px; object-fit: cover"
                            />

                            <div class="card-body">
                                <div
                                    class="d-flex justify-content-between align-items-center mb-2"
                                >
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
                                    <span
                                        v-for="tag in post.tags"
                                        :key="tag.id"
                                        class="badge bg-info me-1"
                                    >
                                        {{ tag.name }}
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 mb-3">
                                    <button
                                        @click="handleLike(post.id)"
                                        class="btn btn-sm"
                                        :class="
                                            post.liked
                                                ? 'btn-danger'
                                                : 'btn-outline-danger'
                                        "
                                    >
                                        <i class="bi bi-heart-fill"></i>
                                        {{ post.likes_count || 0 }}
                                    </button>

                                    <button
                                        @click="handleBookmark(post.id)"
                                        class="btn btn-sm"
                                        :class="
                                            post.bookmarked
                                                ? 'btn-primary'
                                                : 'btn-outline-primary'
                                        "
                                    >
                                        <i class="bi bi-bookmark-fill"></i>
                                    </button>
                                </div>

                                <!-- Comment Form -->
                                <div class="mb-3">
                                    <textarea
                                        v-model="commentBody"
                                        class="form-control mb-2"
                                        rows="2"
                                        placeholder="Write a comment..."
                                    ></textarea>
                                    <button
                                        @click="handleCommentSubmit(post.id)"
                                        class="btn btn-primary btn-sm"
                                    >
                                        Post Comment
                                    </button>
                                    <button
                                        @click="handlePostEdit(post.id)"
                                        class="btn btn-success btn-sm m-2"
                                    >
                                        Post Edit
                                    </button>
                                    <button
                                        @click="handlePostDelete(post.id)"
                                        class="btn btn-danger btn-sm "
                                    >   
                                        Post Delete
                                    </button>
                                </div>

                                <!-- Comments List -->
                                <div class="mt-3">
                                    <div
                                        v-for="comment in post.comments"
                                        :key="comment.id"
                                        class="border-bottom pb-2 mb-2"
                                    >
                                        <p class="mb-1">{{ comment.body }}</p>
                                        <small class="text-muted">
                                            By
                                            {{
                                                comment.user?.name || "Unknown"
                                            }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
