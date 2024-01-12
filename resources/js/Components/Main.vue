<template>
<header class="navbar navbar-light sticky-top bg-dark flex-md-nowrap p-0 shadow bg-light" style="" id="header2">
  	<inertia-link :href="route('dashboard')" style="padding: 5px 10px;">
      <img alt="Porto" width="100" height="35" src="/theme/img/logo.png">
    </inertia-link>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="display:none;">
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#">Arrived  <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Labeled </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Shipped</a>
          </li>
        </ul>
      </div>
  </nav>
<ul class="navbar-nav px-3">
<breeze-dropdown align="right" width="48">
  <template #trigger>
    <span class="inline-flex rounded-md">
        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
            {{ $page.props.auth.user.name }}

            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </span>
  </template>

  <template #content>
    <breeze-dropdown-link :href="route('logout')" method="post" as="button">
        Log Out
    </breeze-dropdown-link>
  </template>
</breeze-dropdown>
</ul>

</header>
  <div class="row">
    <div class="container-fluid">
    <!-- SIDEBAR -->
    <div class="sidebar">
		    <div class="sidebar-menu">

              <inertia-link class="nav-link" :href="route('dashboard')" :class="{active: route().current('dashboard')}" :active="route().current('dashboard')">
                <i class="fas fa-home"></i><span>DASHBOARD</span>
              </inertia-link>

              <inertia-link  v-if="$page.props.auth.user.type != 'customer'" class="nav-link" :href="route('customers')" :class="{active: route().current('customers')}" :active="route().current('customers')">
                <i class="fas fa-external-link-alt"></i><span>Customers</span>
              </inertia-link>

              <inertia-link v-if="$page.props.auth.user.type == 'admin'" class="nav-link" :href="route('manage-users')" :class="{active: route().current('customers')}" :active="route().current('manage-users')">
                <i class="fas fa-external-link-alt"></i><span>Manage Users</span>
              </inertia-link>


              <inertia-link class="nav-link" :href="route('address.suite')" :class="{active: route().current('address.suite')}" :active="route().current('address.suite')">
                <i class="fas fa-external-link-alt"></i><span>Mailing Addresses</span>
              </inertia-link>

              <inertia-link v-if="$page.props.auth.user.type == 'admin'" class="nav-link" :href="route('orders')" :class="{active: route().current('orders')}" :active="route().current('orders')">
                <i class="fas fa-external-link-alt"></i><span>Orders</span>
              </inertia-link>

              <inertia-link v-if="$page.props.auth.user.type == 'customer'" class="nav-link" :href="route('packages')" :class="{active: route().current('packages')}" :active="route().current('packages')">
                <i class="fas fa-external-link-alt"></i><span>Packages</span>
              </inertia-link>

              <inertia-link v-if="$page.props.auth.user.type == 'admin'" class="nav-link" :href="route('pages_list')" :class="{active: route().current('pages_list')}" :active="route().current('pages_list')">
                <i class="fas fa-external-link-alt"></i><span>Manage Pages</span>
              </inertia-link>

              <inertia-link class="nav-link" :href="route('profile')" :class="{active: route().current('profile')}" :active="route().current('profile')">
                <i class="fas fa-external-link-alt"></i><span>Profile</span>
              </inertia-link>

              <inertia-link v-if="$page.props.auth.user.type != 'manager'" class="nav-link" :href="route('addresses')" :class="{active: route().current('addresses')}" :active="route().current('addresses')">
                <i class="fas fa-external-link-alt"></i><span>Address Book</span>
              </inertia-link>

              <inertia-link class="nav-link" :href="route('update-password')" :class="{active: route().current('update-password')}" :active="route().current('update-password')">
                <i class="fas fa-external-link-alt"></i><span>Update Password</span>
              </inertia-link>



          </div>
      </div>
    <!-- SIDEBAR -->

    <div role="main" style="margin-top:25px; margin-left:240px;">
      <div class="col-md-12">
        <slot />
      </div>
    </div>
</div>
</div>
</template>
<style scoped>

.navbar {
  background: var(--white);
  box-shadow: var(--box-shadow);
}
.sidebar-toggle .fas {
	cursor: pointer;
}
.navbar .logo {
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
}
.navbar-brand {
  padding-top: 0;
  font-size: initial;
}

.navbar-nav .nav-link span {
  color: var(--text);
  font-size: 14px;
  font-weight: 700;
  margin-right: 5px;
}
.dropdown-toggle::after {
    border: none;
    margin: 0;
}
.dropdown-menu.show::before {
  content: '';
  width: 19px;
  height: 19px;
  border-bottom: 9px solid #fff;
  border-top: 9px solid transparent;
  border-left: 9px solid transparent;
  border-right: 9px solid transparent;
  position: absolute;
  top: -17px;
  left: 112px;
  z-index: 3;
}
.dropdown-menu[data-bs-popper] {
    left: -52px;
    margin-top: 1.6rem;
}
.dropdown-menu {
  border: 1px solid #e5e5e5;
  padding: 5px;
}
.dropdown-menu.show {
  box-shadow: 0 5px 10px 0 rgba(0,0,0,.1);
}
.dropdown-item.active, .dropdown-item:active, .dropdown-menu .dropdown-item:hover {
  box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgb(66, 136, 204, 0.81);
  background-color: #4288CC;
  color: var(--white);
  border-radius: 3px;
}

/* SIDEBAR STYLE */
.sidebar {
  background-color: #272C33 !important;
  padding: 80px 12px 0;
  position: fixed;
  top: 0;
  width: 220px;
  height: 100%;
  box-shadow: var(--box-shadow);
  transition: 0.3s;
  transition-property: left;
  overflow: overlay;
}
.sidebar .sidebar-menu a {
  color: var(--text);
  display: block;
  width: 100%;
  line-height: 32px;
  text-decoration: none;
  transition: 0.3s;
  margin-bottom: 6px;
  transition-property: background;
}
.sidebar .sidebar-menu .nav-link.active,
.sidebar .sidebar-menu .nav-link:hover,
.sidebar .sidebar-menu .nav-link:focus {
  color: #fff;
  box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgb(66, 136, 204, 0.81);
  background-color: #4288CC;
  border-radius: 3px;
  border:2px solid #4288CC
}
.sidebar .sidebar-menu span {
	font-size: 14px;
	font-weight: 500;
	text-transform: uppercase;
}
.sidebar-menu i {
  padding-right: 10px;
}
.collapse:not(.show) {
    display: unset;
}
.sidebar.collapse {
  width: 80px;
  padding: 80px 0 0;
  transition: .3s;
}
.sidebar.collapse a span {
  display: none;
}
.sidebar.collapse .sidebar-menu i {
  font-size: 20px;
}
.sidebar.collapse .sidebar-menu a {
  padding-left: 28px;
  border-radius: unset;
}

/* MAIN CONTENT STYLE */
.main-content {
  margin-top: 100px;
  margin-left: 250px;
  padding: 15px;
  transition: 0.3s;
}
.form-title h1 {
    text-transform: uppercase;
    letter-spacing: 0.2em;
    font-weight: 400;
    font-size: 1.5rem;
}
.card {
    border: none;
    box-shadow: var(--box-shadow);
    max-width: 600px;
    margin: auto;
}
.card-body {
    padding: 2rem 2rem!important;
}
.form-control:focus,
.btn-check:focus+.btn-danger, .btn-danger:focus {
    box-shadow: none;
}

form-control:focus,
.btn-check:focus+.btn-danger, .btn-danger:focus {
box-shadow: none;
}
.stock-subscription-form .form-group .form-control, .stock-subscription-form .form-group .form-select {
border-color: var(--border);
border-radius: 10px;
height: 49px;
padding-left: 15px;
padding-right: 15px;
}
.form-control:not(.form-control-sm):not(.form-control-lg) {
font-size: 13.6px;
font-size: 0.85rem;
line-height: 1.85;
}
.stock-subscription-form .form-group .form-control, .stock-subscription-form .form-group .form-select {
border-color: var(--border);
border-radius: 10px;
height: 49px;
padding-left: 15px;
padding-right: 15px;
}
.form-control:not(.form-control-lg) {
font-size: 12px;
font-size: 0.75rem;
line-height: 1.3;
}
input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], textarea {
-webkit-appearance: none;
}
[type='text'], [type='email'], [type='url'], [type='password'], [type='number'], [type='date'], [type='datetime-local'], [type='month'], [type='search'], [type='tel'], [type='time'], [type='week'], [multiple], textarea, select {
-webkit-appearance: none;
-moz-appearance: none;
appearance: none;
background-color: #fff;
border-color: #6b7280;
border-width: 1px;
border-radius: 0px;
padding-top: 0.5rem;
padding-right: 0.75rem;
padding-bottom: 0.5rem;
padding-left: 0.75rem;
font-size: 1rem;
line-height: 1.5rem;
}
.form-control {
border-color: rgba(0, 0, 0, 0.09);
}
.form-control {
display: block;
width: 100%;
height: calc(1.5em + .75rem + 2px);
padding: .375rem .75rem;
font-size: 1rem;
font-weight: 400;
line-height: 1.5;
color: #495057;
background-color: #fff;
background-clip: padding-box;
border: 1px solid #ced4da;
border-radius: .25rem;
transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.nav-link{
  padding: .5rem .4rem;
}
</style>
<style>
.form-control{
  border-color: #ced4da !important;
  color:#495057 !important;
}
</style>
<script>
    // import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
    import BreezeApplicationLogo from '@/Components/ApplicationLogo'
    import BreezeDropdown from '@/Components/Dropdown'
    import BreezeDropdownLink from '@/Components/DropdownLink'
    import BreezeNavLink from '@/Components/NavLink'
    import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink'

    export default {
        // components: {
        //     BreezeAuthenticatedLayout,
        // },
        components: {
            BreezeApplicationLogo,
            BreezeDropdown,
            BreezeDropdownLink,
            BreezeNavLink,
            BreezeResponsiveNavLink,
        },

        props: {
            auth: Object,
            errors: Object,
        },
    }
</script>
