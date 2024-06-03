<template>
    <MainLayout>
        <div class="container mx-auto flex justify-between items-center gap-0 py-1 lg:px-10 px-3">
        <div class="flex items-center gap-4 w-full">
            <div class="py-2 sm:py-6 sm:pt-4 h-[90vh] hidden lg:block">
                <div
                    class="min-w-[320px] border rounded-md bg-white shadow-[rgba(0,0,0,0.1)_0px_0px_5px_0px,rgba(0,0,0,0.1)_0px_0px_1px_0px]">
                    <div class="bg-gray-100 sm:px-4 px-2 py-5 rounded-t-md text-xl flex items-center gap-2">
                        <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path
                                d="M64 64C28.7 64 0 92.7 0 128v64c0 8.8 7.4 15.7 15.7 18.6C34.5 217.1 48 235 48 256s-13.5 38.9-32.3 45.4C7.4 304.3 0 311.2 0 320v64c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V320c0-8.8-7.4-15.7-15.7-18.6C541.5 294.9 528 277 528 256s13.5-38.9 32.3-45.4c8.3-2.9 15.7-9.8 15.7-18.6V128c0-35.3-28.7-64-64-64H64zm64 112l0 160c0 8.8 7.2 16 16 16H432c8.8 0 16-7.2 16-16V176c0-8.8-7.2-16-16-16H144c-8.8 0-16 7.2-16 16zM96 160c0-17.7 14.3-32 32-32H448c17.7 0 32 14.3 32 32V352c0 17.7-14.3 32-32 32H128c-17.7 0-32-14.3-32-32V160z" />
                        </svg>
                        <b>Ticket Information</b>
                    </div>
                    <div class="py-3">
                        <ul class="space-y-2 h-full">
                            <li class="text-gray-600 border-b border-gray-100 sm:px-4 px-2 py-1 space-y-1">
                                <p>Requestor</p>
                                <p>{{ inquiry.name }}</p>
                            </li>
                            <li class="text-gray-600 border-b border-gray-100 sm:px-4 px-2 py-1 space-y-1">
                                <p>Department</p>
                                <p>{{ inquiry.department }}</p>
                            </li>
                            <li class="text-gray-600 border-b border-gray-100 sm:px-4 px-2 py-1 space-y-1">
                                <p>Last Updated</p>
                                <p>{{ inquiry.updated_at }}</p>
                            </li>
                            <li class="text-gray-600 border-b border-gray-100 sm:px-4 px-2 py-1 space-y-1">
                                <p class="flex items-center gap-2">
                                    Status<span v-if="inquiry.status === 'open'" class="bg-blue-600 text-white px-4 py-1 rounded-lg text-sm">Open</span>
                                    <span v-else class="bg-red-400 text-white px-4 py-1 rounded-lg text-sm">Close</span>
                                </p>
                            </li>

                        </ul>
                        <div class="flex items-center gap-3 px-2 sm:px-4 py-4">
                            <!-- <button
                                class="bg-stone-950 w-full px-6 py-2 rounded-lg flex items-center justify-center gap-1 font-semibold reply-color"><svg
                                    class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path
                                        d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z" />
                                </svg><span>Reply</span></button> -->
                            <!-- <button
                                class="bg-red-400 w-full px-6 py-2 rounded-lg flex items-center justify-center gap-1 font-semibold"><svg
                                    class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                    <path
                                        d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
                                </svg> <span>Closed</span></button> -->

                                <!-- <inertia-link :href="route('inquirie.index')"
                                class="bg-red-400 w-full px-6 py-2 rounded-lg flex items-center justify-center gap-1 font-semibold">
                                <svg
                                        class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                        <path
                                            d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
                                    </svg> <span>Close</span>
                            </inertia-link> -->
                        </div>

                    </div>
                </div>
            </div>
            <div class="w-full py-2 sm:py-6 sm:pt-4 h-[90vh]">
                <div
                    class="flex-1 py:2 sm:py-6 sm:pt-0 justify-between flex flex-col border rounded-md bg-white shadow-[rgba(0,0,0,0.1)_0px_0px_5px_0px,rgba(0,0,0,0.1)_0px_0px_1px_0px] h-full">
                    <div
                        class="flex flex-col md:flex-row md:items-center justify-between pt-2 sm:pt-0 sm:pb-3 border-b-2 border-gray-200 bg-gray-100 px-2 sm:px-4">
                        <div class="relative flex items-center space-x-4">

                            <div class="flex flex-col gap-0 leading-tight max-w-3xl">
                                <div class="text-2xl mt-1 flex items-center">
                                    <p><span class="font-semibold">Ticket No#</span> <span
                                            class="text-gray-500 mr-3 italic">{{ inquiry.id }}</span></p>
                                </div>
                                <p><span class="font-semibold">Subject:</span> <span class="text-gray-500 pl-1">{{
                                    inquiry.subject }}</span></p>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-3 py-4">
                                <!-- <button
                                    class="bg-stone-950 w-full px-6 py-2 rounded-lg flex items-center justify-center gap-1 font-semibold reply-color">
                                    <svg
                                        class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path
                                            d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z" />
                                    </svg><span>Reply</span></button> -->


                                <!-- <button
                                    class="bg-red-400 w-full px-6 py-2 rounded-lg flex items-center justify-center gap-1 font-semibold"><svg
                                        class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                        <path
                                            d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
                                    </svg> <span>Close</span></button> -->


                                    <inertia-link :href="route('inquirie.index')"
                                class=" back-button w-full px-6 py-2 rounded-lg flex items-center justify-center gap-1 font-semibold">
                                <!-- <svg
                                        class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                        <path
                                            d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
                                    </svg>  -->
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                    <span>Back</span>
                            </inertia-link>
                            </div>

                        </div>
                    </div>

                    <div id="messages" ref="scrollTarget"
                        class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch px-2 sm:px-4">
                        <template v-for="(message , index) in inquiry_messages" :key="message.id">
                            <div class="chat-message">
                                <div class="flex items-end"
                                    :class="{ 'justify-end': message.user_type === 'admin' }">
                                    <div class="flex flex-col space-y-2 text-sm sm:text-base max-w-sm mx-2 order-1"
                                        :class="{
                                            ' items-end': message.user_type === 'admin',
                                            'items-start': message.user_type === 'customer'
                                        }">
                                        <div>
                                            <span class="px-4 py-2 rounded-lg inline-block rounded-br-none  text-black"
                                                :class="{
                                                    'reply-color': message.user_type === 'admin',
                                                    'cust-reply': message.user_type === 'customer'
                                                }">{{ message.message }}</span>
                                        </div>
                                        <p class="text-xs sm:text-sm italic"><span>{{ formatDate(message.created_at) }}
                                            <!-- 2:00 PM Wednesday, Feburary 21, 2024 -->
                                               <!-- {{ new Date(message.created_at).toISOString() }} -->
                                               <!-- {{ format(message.created_at, 'h:mm a EEEE, MMMM d') }} -->
                                               
                                            </span></p>
                                    </div>

                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="border-t-2 border-gray-200 pt-4 mb-2 sm:mb-0 px-2 sm:px-4">
                        <div class="relative flex">
                            <input type="text" placeholder="Write your message!" v-model="form.message" @keydown.enter.prevent="sendMessage"
                                class="focus:ring-[#ffb61a] focus:shadow-[#ffb61a] focus:border-[#ffb61a] border-[#ffb61a] w-full focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-600 pl-2 bg-gray-100 rounded-md py-3" required :readonly="inquiry.status === 'close'">

                            <div class="absolute right-0 items-center inset-y-0 hidden sm:flex">
           
                                <button :disabled="inquiry.status === 'close'" type="button" @click="sendMessage"
                                    class="inline-flex items-center justify-center rounded-lg px-4 py-3 transition duration-500 ease-in-out text-black bg-warning hover:bg-[#e9ac4b] focus:outline-none">
                                    <span class="font-bold">Send</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-6 w-6 ml-2 transform rotate-90">
                                        <path
                                            d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </MainLayout>
</template>
<script>
import MainLayout from '@/Layouts/Main'
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import Pagination from '@/Components/Pagination'
import { format } from 'date-fns';

export default {
    data() {
        return {
            //
            loading: false,
            addInquiry: false,
            inquiry: "",
            inquiry_messages: [],
            form: this.$inertia.form({
                inquiry_messages: this.inquiry_messages,
                inquiry: this.inquiry,
            }),

            form: {
                inquiry_id: "",
                user_id:"",
                message: ""
            },
            errors: {}
        }
    },
    components: {
        BreezeAuthenticatedLayout,
        MainLayout,
        Pagination,
    },
    props: {
        inquiry_messages: Object,
        inquiry: Object,
    },

    watch: {
        params: {
            handler() {
                this.$inertia.get(this.route('inquirie.index'), this.params, { replace: true, preserveState: true });
            }
        }
    },
    methods: {
        //
        sendMessage() {
             this.loading = true;
             this.errors = {};

                this.form.inquiry_id = this.inquiry.id;
                this.form.user_id = this.inquiry.user_id;
            console.log(this.form.message);
            var checkValue = this.form.message.trim();
           if(checkValue.length > 0){
               axios
                   .post(this.route('inquirie.message-send'), this.form,{ replace: true, preserveState: true })
                   .then((response) => {
                       console.log(response);
                       this.form.message = "";
                       this.fetchInquiryMessages();
                   })
                   .catch((error) => {
                       alert(error);
                       this.errors = error.response.data.errors;
                   })
                   .finally(() => {
                       this.loading = false;
                       this.addInquiry = false;
                   });
           }
        },
        //  fetchInquiry() {
        //     this.loading = true;
        //     const url = "inquiry/fetch"

        //     // const router = useRouter();
        //     // const inquiry_id = router.currentRoute.value.params.slug;

        //     var form = {
        //         inquiry_id: this.inquiry.id,
        //         user_id: this.inquiry.user_id
        //     }

        //     this.$axios
        //         .post(this.route('inquirie.list-fetch'), form)
        //         .then((response) => {
        //             this.inquiry = response.data.data.inquiry;
        //             this.inquiry_messages = response.data.data.inquiry_messages;
        //             this.loading = false;
        //         })
        //         .catch((error) => {
        //             console.error(error);
        //             this.loading = false;
        //         });
        // },
        fetchInquiryMessages() {
            this.loading = true;
            this.form.inquiry_id = this.inquiry.id;
            this.form.user_id = this.inquiry.user_id;
            

            axios
                .post(this.route('inquirie.message-list'), this.form)
                .then((response) => {

                    // this.$inertia.replace(this.$page.url, {
                    //     data: {
                    //     inquiry_messages: response.data.data.inquiry_messages,
                    //     inquiry : response.data.data.inquiry
                    //     },
                    //     // preserveState: true
                    // });
                    this.loading = false;
                })
                .catch((error) => {
                    console.error(error);
                    this.loading = false;
                });
        },
        scrollToBottom() {
            const messagesDiv = this.$refs.scrollTarget;
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        },
        formatDate(dateString) {
            const date = new Date(dateString);

            // Format the time
            const hours = date.getHours();
            const minutes = date.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const formattedHours = hours % 12 || 12; // convert to 12-hour format
            const formattedMinutes = minutes < 10 ? '0' + minutes : minutes; // pad with leading zero if needed

            // Format the date
            const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            const dayName = dayNames[date.getDay()];
            const monthName = monthNames[date.getMonth()];
            const day = date.getDate();

            // Combine formatted parts
            return `${formattedHours}:${formattedMinutes} ${ampm} ${dayName}, ${monthName} ${day}`;
        }
    },
    watch: {
        'inquiry_messages.data': {
            handler() {
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            },
            deep: true
        }
    },
    mounted() {

        window.Echo.private('chat-channel')
            .listen('SendMessage', (e) => {
                console.log('ehoooee');
                console.log(e);
                this.inquiry_messages.push(e.message);
            });


        // if(this.inquiry.status === 'open'){

        //     this.intervalId = setInterval(() => {
        //                 this.fetchInquiryMessages();
        //             }, 5000);
        //         }
                this.scrollToBottom();
    },
}
</script>
<style>
#messages {
    height: 300px;
    overflow-y: scroll;
  }
  .reply-color{
    background-color: #FFD36B;
  }
  .cust-reply{
    background-color:#e3e3e3;
  }
  .back-button{
    background-color: chocolate;
}
</style>
