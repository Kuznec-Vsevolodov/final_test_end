<template>
    <section class="content-section row justify-content-center">
        <chat-component v-if="content_type == 'chat'"></chat-component>
        <invite-component v-else-if="content_type=='invite'"></invite-component>  
        <sidebar-component></sidebar-component>
    </section>
</template>

<script>
    import Invite from './inside-components/main-section-components/InviteComponent.vue';
    import Chat from './inside-components/main-section-components/ChatComponent.vue';
    import Sidebar from './inside-components/SidebarComponent.vue';

    export default {
        data(){
            return{
                content_type: 'invite',
            }
        },
        components: {
            'invite-component': Invite,
            'chat-component': Chat,
            'sidebar-component': Sidebar
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
