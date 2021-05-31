<template>
    <div class="col-4">
        <account-component :user_data="user_data" ></account-component>
        <top-component :users_list='top_users'></top-component>
    </div>
</template>

<script>
    import Account from './sidebar-components/AccountComponent.vue';
    import Top from './sidebar-components/TopListComponent.vue';
    export default {
        props: ['user_data'],
        data(){
            return{
                image_path: '/images/avatars/' + this.user_data.avatar,
                top_users: [],
                isModalVisible: false,
            }
        },
        components: {
            'account-component': Account,
            'top-component': Top,
        },
        mounted() {
            this.dataGetter();
        },
        methods: {
            dataGetter(){
                axios.get('/users/top-users').then((response) => {
                    console.log(response.data);
                    response.data.sort(function (a, b) {
                        return b.level+(b.level_status/100) - a.level+(a.level_status/100);
                    });
                    this.top_users = response.data.slice(0, 3);
                });
            } 
        }
    }
</script>