<template>
    <div class="col-md-8">
        <div class="invite-content-text">
            <h1 class="invite-title">Начни играть сейчас!</h1>
            <p class="invite-text col-8">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </p>
        </div>
        <div class="game-cards row">
            <!--<img style="width: 300px" src="images/command-game1.png" alt="">-->
            <div class="game-card col-6">
                <img class="sing-image" src="/images/single-game.png" alt="">
                <div class="join-block">
                    <div class="card-description">
                        <h4 class="card-title">1 на 1</h4>
                        <p class="card-description-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore.
                        </p>
                    </div>
                </div>
                <button class="join-button" @click="showSingModal">
                    Играть
                </button>
            </div>
            <div class="game-card col-6">
                <img class="team-image" src="/images/command-game1.png" alt="">
                <div class="join-block">
                    <div class="card-description">
                        <h4 class="card-title">Командная игра</h4>
                        <p class="card-description-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore.
                        </p>
                    </div>
                </div>
                <button class="join-button" @click="showTeamModal">
                    Играть
                </button>
            </div>
        </div>
        <modal-component v-show="isModalVisible" @close="closeModal" :text='text'></modal-component>
    </div>
</template>

<script>
    import Modal from './ModalComponent.vue';
    export default {
        data () {
            return {
                isModalVisible: false,
                text: '',
            };
        },
        components:{
            'modal-component': Modal,
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            showSingModal() {
                this.text = 'sing';
                this.isModalVisible = true;
            },
            showTeamModal() {
                this.text = 'team';
                this.isModalVisible = true;
            },
            closeModal() {
                this.isModalVisible = false;
            }
        },
    }
</script>

<style scoped>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,300&display=swap');
    .invite-content-text{
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .invite-title{
        color: #C04149;
        font-weight: 900;
        font-size: 64px;
        line-height: 75px;
        text-align: center;
        font-family: 'Roboto', sans-serif;
    }
    .invite-text{
        color: #fff;
        font-size: 14px;
        line-height: 16px;
        text-align: center;
        font-family: 'Roboto', sans-serif;
        font-weight: 400;
    }

    .game-card{
        height: 450px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }
    .game-card img{
        position: absolute;
        z-index: 2;
        bottom: 220px;
    }
    .join-block{
        width: 100%;
        background: #282828;
        height: 253px;
        border-radius: 15px;
        margin-bottom: 15px;
        position: relative;
        display: flex;
        align-items: flex-end;
    }
    .card-description{
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #fff;
        padding-bottom: 47px;
        text-align: center;
    }
    .card-description h4{
        font-weight: bold;
        font-size: 18px;
        line-height: 21px;
        font-family: 'Roboto', sans-serif;
    }
    .card-description p{
        font-size: 14px;
        line-height: 16px;
        width: 75%
    }
    .team-image{
        width: 70%
    } 
    .sing-image{
        width: 40%
    }
    .join-button{
        position: absolute;
        width: 108px;
        height: 28px;
        border: none;
        border-radius: 50px;
        background-color: #C04149;
        color: #fff;
        font-family: 'Roboto', sans-serif;
        font-weight: 400;
    }
</style>