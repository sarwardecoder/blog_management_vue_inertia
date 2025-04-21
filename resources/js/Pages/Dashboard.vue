<script setup>
import { usePage } from '@inertiajs/vue3';
const page = usePage();
console.log(page.props.posts.data);

// defineProps({
//   posts: Array,  // or type: Object, depending on your data structure
//   LoggedUser:Object // loggedUser 
// })
</script>

<template>
<ul>
<li v-for="(post,i) in page.props.posts.data" :key="i">
  {{ post.title }}
</li>

{{ page.props.posts.links }}

</ul>

</template>
<style scoped>
</style>