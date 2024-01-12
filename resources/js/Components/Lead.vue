<template>
    <breeze-authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Update Lead {{lead.first_name}}
            </h2>
        </template>
    <breeze-validation-errors class="mb-4"/>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
        <form @submit.prevent="submit">
        <div>
            <breeze-label for="first_name" value="Name" />
            <breeze-input id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required autofocus autocomplete="first_name" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Update
            </breeze-button>
        </div>
    </form>

    </div>
                </div>
            </div>
        </div>
 
    </breeze-authenticated-layout>
</template>

<script>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
    import BreezeButton from '@/Components/Button'
    import BreezeGuestLayout from '@/Layouts/Guest'
    import BreezeInput from '@/Components/Input'
    import BreezeLabel from '@/Components/Label'
    import BreezeValidationErrors from '@/Components/ValidationErrors'

    export default {
        components: {
            BreezeAuthenticatedLayout,
            BreezeButton,
            BreezeInput,
            BreezeLabel,
            BreezeValidationErrors,
        },
         props: {
            errors: Object,
            lead: Object,
        },
    data() {
            return {
                form: this.$inertia.form({
                    first_name: this.lead.first_name,
                    id:this.lead.id
                })
            }
        },
            methods: {
            submit() {
                this.form.put(this.route('lead',this.lead.id))
                // this.form.post(this.route('Createlead'), {
                //     onFinish: () => this.form.reset(),
                // })
            },

        }
    }
</script>
