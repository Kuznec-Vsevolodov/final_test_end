<template>
    <div>
        <div class="top-list info">
            <div class="up-part">
                <div class="up-title">
                    <img src="/images/users_icon.svg" alt="">
                    <p>Топ игроков</p>
                </div>
                <div class="search-enter">
                    <button><img src="/images/Search.svg" alt=""></button>
                    <input type="text" class="col-10 search-input">
                </div>
            </div>  
            <div class="down-part" v-for="user in users_list">
                <div class="top-user">
                    <div class="user-avatar">
                        <img :src="avatar_image+user.avatar" alt="">
                    </div>
                    <div class="user-info">
                        <div class="user-header-info">
                            <p>{{user.name}}</p>
                            <button><img src="/images/chat_icon.svg" alt=""></button>
                        </div>
                        <div class="user-main-info">
                            <table class="info-table">
                                <tr>
                                    <td>Сыграно:</td>
                                    <td>{{user.games}}<span>|</span> {{user.level}} lvl</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</template>

<script>
    export default {
        props: ['users_list'],
        data(){
            return{
                top_users: [],
                avatar_image: '/images/avatars/'
            }
        },
        mounted() {
            
        },
    }
</script>

<style scoped>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,300&display=swap');

    .info{
        background: #282828;
        border-radius: 15px;
        width: 100%;
        padding: 18px;
        margin-top: 20px
    }
    .up-part{
        color: #fff;
    }
    .up-title{
        display: flex;
        align-items: center;
    }
    .up-title p{
        font-weight: bold;
        font-size: 18px;
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        line-height: 21px;
        margin-bottom: 0px;
        margin-left: 7px;
    }
    .search-enter{
        margin-top: 26px;
    }
    .search-enter button{
        position: absolute;
        z-index: 2;
        border: none;
        background: transparent;
        padding-left: 10px;
        padding-right: 10px;
    }
    .search-input{
        height: 20px;
        border: none;
        border-radius: 50px;
        background: #333333;
        color: #fff;
        font-size: 13px;
        padding-left: 35px;
    }
    .top-user{
        display: flex;
        width: 100%;
        margin-top: 25px;
    }
    .down-part{
        padding-top: 10px;
        padding-bottom: 10px
    }
    .user-info{
        width: 100%;
        color: #fff;
        margin-left: 11px;
    }
    .user-avatar img{
        width: 68px;
        height: 68px;
        border-radius: 50px;
    }
    .user-header-info{
        display: flex;
        justify-content: space-between;
        width: 100%;
        margin-top: 9px;
    }
    .user-header-info p{
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 0px;
        position: relative;
        line-height: 21px;
    }
    .user-header-info p:before{
        content: '';
        position: absolute;
        height: 2px;
        width: 66px;
        background-color: #fff;
        margin-top: 25px;
    }
    .user-header-info button{
        background: transparent;
        padding: 0px;
        border: none;
    }
    .user-main-info{
        width: 100%;
    }
    .info-table{
        width: 80%;
    }
    .info-table td span{
        padding-left: 5px;
        padding-right: 5px;
    }
</style>