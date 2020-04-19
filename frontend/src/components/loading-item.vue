<template>
  <transition name="loading">
    <div class="c-loading" v-if="isLoading">
      <div class="c-loading__inner">
        <v-progress-circular
          indeterminate
          color="grey darken-2"
        />
      </div>
    </div>
  </transition>
</template>

<script>
  import { mapState } from 'vuex'

  export default {
    name: "loading-item",
    computed: {
      ...mapState([
        'isLoading'
      ])
    }
  }
</script>

<style scoped>
  .c-loading {
    z-index: 100;
    background-color: white;
    width: 100%;
    height: 100%;
    position: fixed;
    left: 0;
    top: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.6s;
  }

  .c-loading__inner {
    align-items: center;
  }

  .loading-leave {
    transition: opacity 0.6s;
    opacity: 1;
  }

  .loading-leave-to {
    transition: opacity 0.6s;
    opacity: 0;
  }
</style>
