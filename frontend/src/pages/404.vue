<template>
  <section class="p-404">
    <div class="p-404__inner">
      <h1 class="p-404__heading">404</h1>
      <h2 class="p-404__headingSub">page not found</h2>
      <p class="p-404__text">お探しのページが見つかりませんでした</p>
    </div>
  </section>
</template>

<script>
  export default {
    name: "not-found-page"
  }
</script>

<style scoped>
  .p-404 {
    background-color: #fff;
    text-align: center;
    width: 100%;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .p-404__inner {
    text-align: center;
  }

  .p-404__heading {
    font-family: 'Roboto', sans-serif;
    font-style: italic;
    font-weight: 300;
    font-size: 11.2rem;
    line-height: 1;
  }

  .p-404__headingSub {
    font-family: 'Roboto', sans-serif;
    font-style: italic;
    font-weight: 300;
    font-size: 2.6rem;
    line-height: 1;
    margin-bottom: 16px;
  }

  .p-404__text {
    font-size: 1.4rem;
  }

  @media screen and (max-width: 621px) {
    .p-404__inner {
      width: 100%;
      margin: 0 3.8%;
    }
    .p-404__heading {
      font-size: 8rem;
    }
    .p-404__headingSub {
      font-size: 2rem;
    }
    .p-404__text {
      font-size: 1.1rem
    }
  }
</style>
