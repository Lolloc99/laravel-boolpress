<template>
    <div class="card mb-3">
        <!-- <img class="card-img-top" src="..." alt="Card image cap" /> -->
        <div class="card-body">
        <p>Category: {{ categoryName }}</p>
        <h5 class="card-title">{{ post.title }}</h5>
        <p class="card-text">
            {{ troncateText(post.content, 50) }}
        </p>
        </div>
        <div class="card-body">
            <router-link :to="{ name: 'single-post', params: { slug: post.slug } }" class="card-link">Leggi post</router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: "PostCard",
    props: {
        post: Object,
    },

    computed: {
        categoryName() {
            return this.post.category ? this.post.category.name : 'nessuna categoria';
        }
    },

    methods: {
        troncateText(text, maxCharNumber) {
            // se il testo supera MaxCharNumber allora taglia il testo e aggiunge "..."
            if (text.length > maxCharNumber) {
                return text.substr(0, maxCharNumber) + '...';
            }
            return text;
        }
    }
}

</script>

<style>

</style>