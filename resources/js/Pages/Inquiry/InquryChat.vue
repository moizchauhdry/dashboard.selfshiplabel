<template>
    <MainLayout>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light text-xl d-flex align-items-center">
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
                                    <div>Created at</div>
                                    <div>{{ formatDateTime(inquiry.created_at) }}</div>
                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center border-bottom">
                                    <div>Updated at</div>
                                    <div>{{ formatDateTime(inquiry.updated_at) }}</div>
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

                <div class="col-md-8">
                    <div class="d-flex flex-column justify-content-between card shadow-sm">
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
                                    class="btn btn-primary btn-block d-flex align-items-center justify-content-center">
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
                                            <div class="mt-2 mb-1" style="text-align: justify;">
                                                <span class="badge p-3"
                                                    :class="message.user_type === 'admin' ? 'badge-secondary' : 'badge-info'"
                                                    style="font-size: 1rem; border-radius: 20px; white-space: pre-wrap;">
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
                                <input type="text" placeholder="Type a message" v-model="form.message"
                                    @keydown.enter.prevent="sendMessage" class="form-control bg-light border-warning"
                                    required :readonly="inquiry.status === 'close'">
                                <button :disabled="inquiry.status === 'close'" type="button" @click="sendMessage"
                                    class="btn btn-warning ml-2"> Send
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
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            const dayName = dayNames[date.getDay()];
            const monthName = monthNames[date.getMonth()];
            const day = date.getDate();

            // Combine formatted parts
            return `${formattedHours}:${formattedMinutes} ${ampm}, ${monthName} ${day}`;
        },
        formatDateTime(date) {
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false, // Set to true for 12-hour format with AM/PM
            };
            return new Date(date).toLocaleString(undefined, options);
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

        this.scrollToBottom();
    },
}
</script>

<style>
#messages {
    height: 500px;
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
