<template>
    <div class="container" id="app">
      <meta charset="utf-8">
      <meta content="width=device-width,initial-scale=1,minimal-ui" name="viewport">
      <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,500,700,400italic|Material+Icons">
      <tableTemplate></tableTemplate>
    </div>
</template>

<script>

import tableTemplate from './components/tableTemplate'

export default {
  name: 'App',
  components:{
    tableTemplate,
  },

  data() {
    return {
      source: "",
    }
  },
  methods: {

  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: left;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
