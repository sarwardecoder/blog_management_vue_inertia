<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'



const props = defineProps({
    posts: Array,
    loggedUser: Object
})

const logout = () => {
    form.post("/user/logout", {

        forceFormData: true,
        preserveScroll: true,
    });
}
const form = useForm({})
const searchQuery = ref('')
const commentBody = ref('')

</script>

<template>
    <div class="container">



        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="container-fluid">
                <Link class="navbar-brand" href="/">Blog Site</Link>
<button class="btn btn-primary btn-md">                <Link class="nav-link rounded bg-primary" href="/user/login">Login</Link>
</button>
            </div>
        </nav>
         <button class="btn btn-primary btn-md">
            <Link class="nav-link active" href="/user/dashboard/post/create">Create New Post
            </Link>
        </button>



        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4">Our Blog Site</h1>
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
                                style="height: 200px; object-fit: cover;" />

                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0">{{ post.title }}</h5>
                                    <small class="text-muted">
                                        {{ new Date(post.created_at).toLocaleDateString() }}
                                    </small>
                                </div>

                                <h6 class="text-muted">By: {{ post.user?.name || 'Unknown' }}</h6>
                                <p class="card-text">{{ post.content }}</p>

                                <!-- Tags -->
                                <div v-if="post.tags?.length" class="mb-3">
                                    <span v-for="tag in post.tags" :key="tag.id" class="badge bg-info me-1">
                                        {{ tag.name }}
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 mb-3">
                                    <button @click="handleLike(post.id)" class="btn btn-sm"
                                        :class="post.liked ? 'btn-danger' : 'btn-outline-danger'">
                                        <i class="bi bi-heart-fill"></i>
                                        {{ post.likes_count || 0 }}
                                    </button>

                                    <button @click="handleBookmark(post.id)" class="btn btn-sm"
                                        :class="post.bookmarked ? 'btn-primary' : 'btn-outline-primary'">
                                        <i class="bi bi-bookmark-fill"></i>
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
                                <!-- Comment Form end -->


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
                                <!-- Comments List end -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}
</style>