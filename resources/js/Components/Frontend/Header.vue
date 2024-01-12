<template>
  <!-- NAVBAR SECTION  -->

  <div class="container-fluid navbar1">
    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-3">
      <div class="container">
        <a class="navbar-brand" href="#">
          <img src="https://shippingxps.com/wp-content/uploads/2022/09/logo-1.png.webp" height="50" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <inertia-link :href="route('auctions.index')" class="nav-link active" aria-current="page">Auction</inertia-link>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="#">Shipingxps</a>
            </li>
            <template v-if="!$page.props.auth.user">
              <li class="nav-item">
                <a :href="route('login')" class="btn btn-danger shadow-0 me-1">Login </a>

              </li>
              <li class="nav-item">
                <a :href="route('register')" class="btn btn-warning">New Account</a>

              </li>
            </template>
            <template v-else>
              <li class="nav-item">
                <a :href="route('dashboard')" class="btn btn-warning">Dashboard </a>

              </li>
            </template>


          </ul>
        </div>
      </div>
    </nav>

  </div>
  <!-- NAVBAR SECTION  -->
</template>

<script>
export default {
  name: "Header"
}
</script>

<style scoped>

</style>