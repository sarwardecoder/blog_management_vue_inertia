<script setup>
import NavBar from './NavBar.vue';
import { Link,usePage, useForm } from '@inertiajs/vue3'
import PublicPost from './Post/PublicPost.vue'

const props = defineProps({
    posts: Object,       // posts is usually a pagination object from Laravel (not an array)
    LoggedUser: Object   // this can be null or an object
});


const form = useForm({});

const logout = ()=>{
    form.post("/user/logout", {
        
        forceFormData: true,
        preserveScroll: true,
    });
}

</script>
<template>
    <div class="container">
 <!--  NavBar -->

            <!-- <NavBar :LoggedUser="LoggedUser" /> -->

<!--  NavBar Ends here-->

        <public-post :posts="posts" :LoggedUser="LoggedUser" />
    </div>
</template>