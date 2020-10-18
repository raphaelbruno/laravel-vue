<template>
    <div class="container h-100 d-flex flex-column">
        <div class="home content my-auto">
            <div class="title m-b-md">Laravel <i class="fas fa-heart heart"></i> Vue</div>
            
            <div class="links">
                <a href="https://laravel.com/docs">Docs</a>
                <a href="https://laracasts.com">Laracasts</a>
                <a href="https://laravel-news.com">News</a>
                <a href="https://blog.laravel.com">Blog</a>
                <a href="https://nova.laravel.com">Nova</a>
                <a href="https://forge.laravel.com">Forge</a>
                <a href="https://vapor.laravel.com">Vapor</a>
                <a href="https://github.com/laravel/laravel">GitHub</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component Home was loaded!')
        }
    }
</script>

<style>
  .home.content {
      text-align: center;
  }

  .home .title {
      font-weight: 200;
      font-size: 84px;
  }

  .home .links > a {
      color: #636b6f;
      padding: 0 25px;
      font-size: 13px;
      font-weight: 600;
      letter-spacing: .1rem;
      text-decoration: none;
      text-transform: uppercase;
  }

  .home .m-b-md {
      margin-bottom: 30px;
  }

  .home .heart {
      color: #41b883;
  }

</style>