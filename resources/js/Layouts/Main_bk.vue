<template>
<header class="navbar navbar-light sticky-top bg-dark flex-md-nowrap p-0 shadow bg-light" style="" id="header2">
  	<inertia-link :href="route('dashboard')">
      <img alt="shippingxps" width="237" height="55" src="/theme/img/cropped-logo@2x.png">
    </inertia-link>
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
  <div class="container-fluid">
  <div class="row" style="margin-top:25px;">
  	<div id="sidebarMenuDiv" class="col-md-2 col-lg-2 col-sm-2 col-xl-2">
    <nav id="sidebarMenu" class="d-md-block sidebar">
      <div class="">
        <ul class="nav flex-column">
            <li class="nav-item">
              <inertia-link class="nav-link" :href="route('dashboard')" :class="{active_dashboard: route().current('dashboard')}" :active="route().current('dashboard')">
              DASHBOARD
              </inertia-link>
            </li>  
            <li class="nav-item">
              <inertia-link class="nav-link" :href="route('leads')" :class="{active: route().current('leads')}"  :active="route().current('leads')" v-if=" $page.props.auth.user.type =='admin' ">
                LEADS
              </inertia-link>
            </li>
            <li class="nav-item">
              <inertia-link class="nav-link" :href="route('category_list')" :class="{active: route().current('category_list')}"  :active="route().current('category_list')" v-if=" $page.props.auth.user.type =='admin' ">
                BLOG CATEGORIES
              </inertia-link>
            </li>
            <li class="nav-item">
              <inertia-link class="nav-link" :href="route('blogs_list')" :class="{active: route().current('blogs_list')}"  :active="route().current('blogs_list')" v-if=" $page.props.auth.user.type =='admin' ">
                BLOG POSTS
              </inertia-link>
            </li>
        </ul>
      </div>
    </nav>
   </div>
    <div class="col-md-9 col-sm-9 col-lg-9 bg-white">
    	
     <slot />
    </div>
   <!--  </main> -->
</div>
</div>
</template>
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
