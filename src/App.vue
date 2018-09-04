<template>
  <div id="app">
    <Landing/>
  </div>
</template>

<script>
import Landing from './components/Landing'

export default {
  name: 'App',
  components: {
    Landing
  }
}
</script>

<style>
#app {
  width: 90%;
  max-width: 1000px;
  margin-left: auto;
  margin-right: auto;
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
