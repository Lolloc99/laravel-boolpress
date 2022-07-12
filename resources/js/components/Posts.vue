<template>
  <div class="container">
    <h2>Lista dei post</h2>
    <div class="row row-cols-3">
      <!-- Single post -->
      <div v-for="post in posts" :key="post.id" class="col">
        <div class="card mb-3">
          <!-- <img class="card-img-top" src="..." alt="Card image cap" /> -->
          <div class="card-body">
            <h5 class="card-title">{{ post.title }}</h5>
            <p class="card-text">
              {{ troncateText(post.content, 50) }}
            </p>
          </div>
          <!-- <ul class="list-group list-group-flush">
            <li class="list-group-item">Cras justo odio</li>
          <div class="card-body">
            <a href="#" class="card-link">Card link</a>
            <a href="#" class="card-link">Another link</a>
          </div> -->
        </div>
      </div>
    </div>
  </div>

</template>

<script>
export default {
    name: "Posts",
    data() {
        return {
            posts: [],
        };
    },

    created() {
        this.getPosts();
    },

    methods: {
        getPosts() {
            axios.get("http://127.0.0.1:8000/api/posts").then((resp) => {
                this.posts = resp.data.results;
            })
        },
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