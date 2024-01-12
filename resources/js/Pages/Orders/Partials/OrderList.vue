<template>
    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center text-sm capitalize">
            <thead>
                <tr>
                    <th scope="col">SR #</th>
                    <th scope="col">Order ID</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Order From</th>
                    <th scope="col">Tracking #</th>
                    <th scope="col">Warehouse</th>
                    <th scope="col">Received Date</th>
                    <th scope="col">Status</th>
                    <template v-if="auth.user.type == 'admin' || auth.user.type == 'manager'">
                    <th scope="col"></th>
                    </template>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(order,index) in orders" :key="order.id">
                    <td>{{ ++index }}</td>
                    <td>{{ order.id}}</td>
                    <td>
                        <inertia-link :href="route('customers.show', order?.customer?.id)" class="btn btn-link">
                        # {{ siuteNum(order?.customer?.id) }} - {{ order?.customer?.name }}
                        </inertia-link>
                    </td>
                    <td>{{ order.received_from }}</td>
                    <td>{{ order.tracking_number_in }}</td>
                    <td>{{ order?.warehouse?.name }}</td>
                    <td>{{ order.created_at }}</td>
                    <td>
                        <span v-bind:class="getLabelClass(order.status)">{{ order.status }}</span>
                    </td>
                    <template v-if="auth.user.type == 'admin' || auth.user.type == 'manager'">
                    <td>
                        <inertia-link class="btn btn-primary btn-sm m-1" :href="route('order.edit', order.id)"><i class="fa fa-pencil-alt mr-1"></i>Edit</inertia-link>
                        <inertia-link class="btn btn-info btn-sm m-1" :href="route('orders.show', order.id)"><i class="fa fa-list mr-1"></i>Detail</inertia-link>
                    </td>
                    </template>
                </tr>
                <tr v-if="orders.length == 0">
                    <td class="text-primary text-center text-uppercase" colspan="9">There are no orders found.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "Order List",
        props: {
            auth: Object,
            orders:Object,
            filter:Object, 
        },
        data() {
            return {
                // 
            }
        },
        methods:{
            getLabelClass(status){
                switch(status) {
                    case 'arrived':
                    return 'label bg-success p-1 text-white';
                    break;
                    case 'labeled':
                    return 'label bg-info p-1 text-white';
                    break;
                    case 'shipped':
                    return 'label bg-warning p-1';
                    break;
                    case 'delivered':
                    return 'label bg-success p-1 text-white';
                    break
                    case 'rejected':
                    return 'label bg-danger p-1 text-white';
                    break;
                    default:
                    return 'label bg-primary p-1 text-white';
                }
            },
            siuteNum(user_id){
                return 4000 + user_id;
            },
        }
    }
</script>
