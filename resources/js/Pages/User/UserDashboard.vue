<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import PublicPost from '../Post/PublicPost.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    posts: Array,
    loggedUser: Object
})



const handleSubmit = () => {
  form.post(route('post.store'), {
    forceFormData: true,
    preserveScroll: true
  })
}

const form = useForm({})
const searchQuery = ref('')
const commentBody = ref('')

const handleLike = async (postId) => {
    form.post(`/posts/${postId}/likes`, {
        preserveScroll: true,
        onSuccess: () => {
            // Handle success if needed
        }
    })
}

const handleBookmark = async (postId) => {
    form.post(`/posts/${postId}/bookmarks`, {
        preserveScroll: true,
        onSuccess: () => {
            // Handle success if needed
        }
    })
}

const handleCommentSubmit = async (postId) => {
    form.post(`/posts/${postId}/comments`, {
        body: commentBody.value
    }, {
        preserveScroll: true,
        onSuccess: () => {
            commentBody.value = ''
        }
    })
}

const handleSearch = () => {
    form.get('/posts', {
        search: searchQuery.value
    })
}
</script>

<template>
    <div class="container">

        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item active">
                            <a class="nav-link active" href="/user/dashboard">My Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/user/dashall">All Posts</a>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <!-- <img :src="LoggedUser.img ? '/' + LoggedUser.img : '/uploads/default.jpg'" width="30" height="30" class="rounded-circle" /> -->
                            <div class="header-info">
                                <!-- <span>{{ LoggedUser.name }}</span> -->
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <button @click="logout" class="dropdown-item text-danger">Logout</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

<!-- user posts here  -->
 <!-- create new post -->

<button  class="btn btn-primary btn-md">   <a class="nav-link active" href="/user/dashboard/post/create">Create New Post</a>
</button>
 <public-post :posts="posts" :LoggedUser="LoggedUser" />


    </div>
</template>