<template>
  <div class="container">
    <h1>Errore 404</h1>
    <h2>La destinazione che cerchi non esiste</h2>
    <router-link :to="{name: 'home'}">Torna alla home</router-link>
  </div>
</template>

<script>
export default {
    name: "NotFound",
}
</script>

<style>

</style>