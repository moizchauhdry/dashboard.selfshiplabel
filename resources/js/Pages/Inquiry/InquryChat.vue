<template>
    <MainLayout>
        <div class="container-fluid d-flex justify-content-between align-items-center py-1 px-3 px-lg-5">
            <div class="d-flex align-items-center w-100">
                <div class="d-none d-lg-block py-2 py-sm-4 h-100" style="min-height: 90vh;">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light text-xl d-flex align-items-center">
                            <svg class="mr-2" width="24" height="24" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 576 512">
                                <path
                                    d="M64 64C28.7 64 0 92.7 0 128v64c0 8.8 7.4 15.7 15.7 18.6C34.5 217.1 48 235 48 256s-13.5 38.9-32.3 45.4C7.4 304.3 0 311.2 0 320v64c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V320c0-8.8-7.4-15.7-15.7-18.6C541.5 294.9 528 277 528 256s13.5-38.9 32.3-45.4c8.3-2.9 15.7-9.8 15.7-18.6V128c0-35.3-28.7-64-64-64H64zm64 112l0 160c0 8.8 7.2 16 16 16H432c8.8 0 16-7.2 16-16V176c0-8.8-7.2-16-16-16H144c-8.8 0-16 7.2-16 16zM96 160c0-17.7 14.3-32 32-32H448c17.7 0 32 14.3 32 32V352c0 17.7-14.3 32-32 32H128c-17.7 0-32-14.3-32-32V160z" />
                            </svg>
                            <b>Ticket Information</b>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center border-bottom">
                                    <div>Requestor</div>
                                    <div>{{ inquiry.name }}</div>
                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center border-bottom">
                                    <div>Department</div>
                                    <div>{{ inquiry.department }}</div>
                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center border-bottom">
                                    <div>Last Updated</div>
                                    <div>{{ inquiry.updated_at }}</div>
                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center border-bottom">
                                    <div>Status</div>
                                    <div>
                                        <span v-if="inquiry.status === 'open'" class="badge badge-primary">Open</span>
                                        <span v-else class="badge badge-danger">Close</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="w-100 py-2 py-sm-4 h-100" style="min-height: 90vh;">
                    <div class="d-flex flex-column justify-content-between card h-100 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light">
                            <div class="d-flex align-items-center">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <p class="h4 mb-0"><strong>Ticket No#</strong> <span class="text-muted">{{
                                                inquiry.id }}</span></p>
                                    </div>
                                    <p><strong>Subject:</strong> <span class="text-muted">{{ inquiry.subject }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="py-2">
                                <a :href="route('inquirie.index')"
                                    class="btn btn-light btn-block d-flex align-items-center justify-content-center">
                                    <i class="fa fa-arrow-left mr-2" aria-hidden="true"></i>
                                    Back
                                </a>
                            </div>
                        </div>

                        <div id="messages" ref="scrollTarget" class="card-body p-3 overflow-auto">
                            <template v-for="(message, index) in inquiry_messages" :key="message.id">
                                <div class="chat-message">
                                    <div class="d-flex"
                                        :class="{ 'justify-content-end': message.user_type === 'admin' }">
                                        <div :class="{ 'text-right': message.user_type === 'admin', 'text-left': message.user_type === 'customer' }"
                                            class="d-flex flex-column">
                                            <div class="mb-2">
                                                <span class="badge"
                                                    :class="message.user_type === 'admin' ? 'badge-secondary' : 'badge-info'">
                                                    {{ message.message }}
                                                </span>
                                            </div>
                                            <p class="small text-muted"><i>{{ formatDate(message.created_at) }}</i></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="card-footer">
                            <div class="d-flex">
                                <input type="text" placeholder="Write your message!" v-model="form.message"
                                    @keydown.enter.prevent="sendMessage" class="form-control bg-light border-warning"
                                    required :readonly="inquiry.status === 'close'">
                                <button :disabled="inquiry.status === 'close'" type="button" @click="sendMessage"
                                    class="btn btn-warning ml-2">
                                    <span class="font-weight-bold">Send</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="ml-2" width="20" height="20">
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

    </MainLayout>
</template>
<script>
import MainLayout from '@/Layouts/Main'
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import Pagination from '@/Components/Pagination'
import { format } from 'date-fns';
import { db } from '@/bootstrap';

import { collection, query, addDoc, where, orderBy, onSnapshot, serverTimestamp } from 'firebase/firestore';


export default {
    data() {
        return {
            //
            loading: false,
            addInquiry: false,
            inquiry: "",
            inquiry_messages: [],
            form: this.$inertia.form({
                // inquiry_messages: this.inquiry_messages,
                inquiry: this.inquiry,
            }),

            form: {
                inquiry_id: "",
                user_id: "",
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
        // inquiry_messages: Object,
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
        async sendMessage() {
            this.loading = true;
            this.errors = {};

            this.form.inquiry_id = this.inquiry.id;
            this.form.user_id = this.inquiry.user_id;
            var checkValue = this.form.message.trim();
            if (checkValue.length > 0) {

                await addDoc(collection(db, 'inquiry_messages'), {
                    inquiry_id: this.form.inquiry_id,
                    user_id: this.form.user_id,
                    user_type: 'admin',
                    message: this.form.message.trim(),
                    created_at: serverTimestamp(),
                    updated_at: serverTimestamp()
                });

                this.form.message = '';
            }
        },
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

            if (!dateString || !dateString.seconds) {
                return "Loading ...";
            }
            const date = new Date(dateString.seconds * 1000);

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

        const messagesCollection = collection(db, 'inquiry_messages');
        const messagesQuery = query(messagesCollection,
            where('inquiry_id', '==', this.inquiry.id),
            orderBy('created_at'));
        onSnapshot(messagesQuery, (snapshot) => {
            let messages = snapshot.docs.map(doc => ({
                id: doc.id,
                ...doc.data()
            }));
            this.inquiry_messages = messages;
        });

        // window.Echo.private('chat-channel')
        //     .listen('SendMessage', (e) => {
        //         console.log('ehoooee');
        //         console.log(e);
        //         this.inquiry_messages.push(e.message);
        //     });

        this.scrollToBottom();
    },
}
</script>
<style>
#messages {
    height: 300px;
    overflow-y: scroll;
}

.reply-color {
    background-color: #FFD36B;
}

.cust-reply {
    background-color: #e3e3e3;
}

.back-button {
    background-color: chocolate;
}
</style>
