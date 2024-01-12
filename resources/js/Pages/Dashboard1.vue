<template>
    <MainLayout>
    <div>
    </div>
    </MainLayout>
</template>

<script>
    import MainLayout from '@/Layouts/Main'
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'

    export default {
        components: {
            BreezeAuthenticatedLayout,
            MainLayout
        },

        props: {
            auth: Object,
            errors: Object,
        },
    }
</script>
