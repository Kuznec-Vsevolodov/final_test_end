<template>
        <div>
            <div class="acc-info-main-block info">
                <div class="up-part">
                    <img :src='image_path' alt="">
                    <div class="main-info">
                        <div class="main-info-up">
                            <h3>{{user_data.name}}</h3>
                            <p>{{user_data.level}} lvl</p>
                        </div>
                        <p class="online-handler">Online</p>
                    </div>
                </div>
            </div>
            <div class="down-part info">
                    <table class="data-table">
                        <tr>
                            <td>Счёт:</td>
                            <td>{{user_data.wallet}}$</td>
                        </tr>
                        <tr>
                            <td>Всего игр:</td>
                            <td>{{user_data.games}}</td>
                        </tr>
                    </table>
                </div>
        </div>
</template>

<script>
    export default {
        props: ['user_data', 'user_position'],
        data(){
            return{
                image_path: '/images/avatars/' + this.user_data.avatar
            }
        },
        mounted() {
            console.log(this.user_data);
        }
    }
</script>

<style scoped>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,300&display=swap');

    .info{
        background: #282828;
        border-radius: 15px;
        width: 100%;
        padding: 18px;
    }
    .up-part{
        display: flex;
    }
    .down-part{
        margin-top: 6px;
    }
    .up-part img{
        width: 67px;
        height: 67px;
        border-radius: 50px;
    }
    .main-info{
        width: 100%;
        margin-left: 13px;
    }
    .main-info-up{
        display: flex;
        color: #fff;
        margin-top: 15px;
        align-items: center;
        justify-content: space-between;
    }
    .main-info-up h3{
        font-weight: bold;
        font-size: 24px;
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        margin: 0px;
    }
    .main-info-up p{
        opacity: 0.5;
        font-size: 18px;
        font-family: 'Roboto', sans-serif;
        font-weight: 400;
        margin: 0px;
    }
    .online-handler{
        color: #fff;
        font-size: 14px;
        font-family: 'Roboto', sans-serif;
        font-weight: 400;
    }
    .data-table{
        width: 100%;
        color: #fff;
    }
    .data-table tr td{
        line-height: 17px;
        font-size: 14px;
        padding: 0px;
        font-family: 'Roboto', sans-serif;
        width: 50%;
    }
</style>
