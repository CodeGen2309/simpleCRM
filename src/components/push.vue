<template>
  <transition name='fadeUpAnim'>
    <p class="push">HELLLO BITHEZZZZ!!!</p>
  </transition>
</template>

<script>
export default {
  data: () => ({
    timeout: 5000,
  })
}
</script>

<style>
.push {
  background: rgba(0, 0, 0, .6);
  padding: 20px;
  border-radius: 8px;
  color: white;
  transition: .3s;
}
</style>