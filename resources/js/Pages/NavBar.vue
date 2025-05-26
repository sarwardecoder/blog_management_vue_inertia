<script setup>
import { reactive, ref } from "vue";
import { Link, useForm, router } from "@inertiajs/vue3";

defineProps({
    LoggedUser : Object
})

const form = useForm({});

const logout = () => {
    form.post("/user/logout", {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
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
                <li class="nav-item" v-if="!LoggedUser">
                    <Link  class="nav-link active" href="/user/login" 
                    >Login Here</Link
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
                <img
                :src="
                                    LoggedUser.img
                                        ? '/' + LoggedUser.img
                                        : '/uploads/default.jpg'
                                "
                                width="50"
                                height="40"
                                class="rounded-circle"
                                />
                                <div class="header-info">
                                    <span>{{ LoggedUser.name }}</span>
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
</template>

<style scoped>
</style>