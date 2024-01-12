<template>
        <div v-if="errorMessage">
            <div class="alert alert-danger">
                {{ errorMessage }}
            </div>
        </div>
        <div v-if="successMessage">
            <div class="alert alert-success">
                {{ successMessage }}
            </div>
        </div>
</template>

<script>
    export default {
        computed: {
            successMessage() {
                return this.$page.props.flash.success
            },
            errorMessage() {
                return this.$page.props.flash.error
            },
        }
    }
</script>
