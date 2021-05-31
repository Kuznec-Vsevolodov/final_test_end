<template>
    <div class="col-md-8 chat-content-block">
        <div class="chat-block">
            <div class="message-list" id="messages_list"  >
                    <div class="message-row" v-for="message in messages" v-if="messages != []" v-on:change="autoScroller()" ref="messagesList">
                        <div class="outside_message" v-if="message.author != user.name">
                            <div class="chat-users-list" v-if="message.author == 'NoOne'">
                                    <p>{{message.text}}</p>
                            </div>
                            <div class="main_message-out message_out" v-else-if="
                                    messages[indexChecker(message)-1].author != message.author">
                                <img :src="'/images/avatars/'+message.author_avatar" alt="" class="avatar-image">
                                <div class="message-struct">
                                    <div class="message-header" v-if="
                                            messages[indexChecker(message)-1].author != message.author
                                        ">
                                        <h6 class="acc-name">{{message.author}}</h6>
                                        <p class="time">{{message.created}}</p>
                                    </div>
                                    <div class="message-body">
                                        <p>{{message.text}}</p>
                                        <img class="message_img" v-if="message.image != false" :src="'/images/chats/'+message.image">
                                    </div>
                                </div>
                            </div>
                            <div class="message_out inside_out_message" v-else-if="messages[indexInChecker(message)+1].author == message.author">
                                <div class="message-struct">
                                    <div class="message-body">
                                        <p>{{message.text}}</p>
                                        <img class="message_img" v-if="message.image != false" :src="'/images/chats/'+message.image">
                                    </div>
                                </div>
                            </div>
                            <div class="message_out end_out_message" v-else>
                                <div class="message-struct">
                                    <div class="message-body">
                                        <p>{{message.text}}</p>
                                        <img class="message_img" v-if="message.image != false" :src="'/images/chats/'+message.image">
                                    </div>
                                </div>
                            </div>
                        </div>    
                        <div class="message-in-row" v-else>
                            <div class="message-in">
                                <p>{{message.message}}</p>  
                                <img class="message_img" v-if="message.image != false" :src="'/images/chats/'+message.image">  
                            </div> 
                            <p class="time time-in">{{message.created}}</p>
                        </div>
                    </div>     
            </div>
            <div class="input-data-block">
                <div class="main-input">
                    <input type="text" v-model="dataInput" placeholder="Сообщение..." class="message-input" @keyup.enter="sendMessage">
                    <input type="file" ref="imgInputData" id="image-out" class="image-input" v-on:change="imgUpload()">
                    <label for="image-out" class="image-label"><img src="/images/paper-clip.svg"></label>
                    <button type="submit" @click="sendMessage"><img src="/images/sender.svg" alt=""></button>
                </div>
                <div class="def-buttons" v-show="conditionVisibility">
                    <button @click="positiveRequest">Задание выполнено</button>
                    <button @click="nagativeRequest">Задание не выполнено</button>
                </div>
            </div>
        </div>
        <div class="service_accidense">
            <button class="col-6">Нарушение правил сервиса</button>
        </div>    
    </div>
</template>

<script>
    export default {
        props: ['chat_channel_id', 'user'],
        data(){
            return{
                dataInput: '',
                messages: [],
                conditionVisibility: false,
                chat_type: '',
                imgInputData: '',
            }
        },
        mounted() {
            this.socketConnect(),
            this.getMessages(),
            this.autoScroller()
        //     console.log(this.user.game_in);
        //     this.mainUserDef(),
        //     this.getMessages(),
        //     Echo.channel(this.user.game_in+'-chat-'+this.chat_channel_id)
        //         .listen('.chat-event', (e) => {
        //             if(this.messages == []){
        //                 this.messages = [e.message];
        //             }else{
        //                 this.messages.push(e.message);
        //             }
                    
        //         });
        //     Echo.channel('chat-users-'+this.user.game_in+'-'+this.chat_channel_id)
        //         .listen('.chat-users-event', (e) => {
        //             if(e.main_user != null){
        //                 if(e.main_user == this.user.name){
        //                     this.conditionVisibility = true;
        //                 }else if(e.main_user != this.user.name){
        //                     console.log(e.main_user);
        //                     this.conditionVisibility = false;
        //                 }
        //             }
        //             if(e.status != null){
        //                 if(e.status == false){
        //                     this.conditionVisibility = !this.conditionVisibility;
        //                     console.log(this.conditionVisibility);
        //                 }else if(e.status == true){
        //                     window.location.replace('http://127.0.0.1:8000/');
        //                 }else{
        //                     console.log('Вы проиграли');
        //                     window.location.replace('http://127.0.0.1:8000/');
        //                 }
        //             }
                    
        //             console.log(e.status);
        //         });    
        //         if(this.user.game_in == 'team'){
        //                 Echo.channel('lefted-users-'+this.chat_channel_id)
        //                     .listen('.lefted-users-event', (e) => {
        //                         if(e.user_name == this.user.name){
        //                             window.location.replace('http://127.0.0.1:8000/');
        //                         }
        //                     });
        //         }   
        },
        methods: {
            socketConnect(){
                var connectionOptions =  {
                    "force new connection" : true,
                    "reconnectionAttempts": "Infinity", //avoid having user reconnect manually in order to prevent dead clients after a server restart
                    "transports" : ["websocket"]
                };
                var socket = io("http://localhost:3000", connectionOptions);
            },
            autoScroller(){
                console.log(this.$refs.messagesList);
                this.$refs.messagesList.scrollTop = this.$refs.messagesList.scrollHeight;
            },
            chatTypeDef(){
                axios({
                    method: 'post',
                    url: 'http://127.0.0.1:8000/chat/type-def/'
                })
                .then((response) => { 
                    this.chat_type = response.data;
                    console.log(this.chat_type);
                });  
            },
            mainUserDef(){
                axios({
                    method: 'post',
                    url: 'http://127.0.0.1:8000/chat/'+this.user.game_in+'-user-def/',
                    params: { chat_id: this.chat_channel_id}
                })
                .then((response) => { 
                    if(response.data == this.user.name){
                        this.conditionVisibility = true;
                        console.log(this.conditionVisibility);
                    }       
                });   
            },
            imgUpload(){
                this.imgInputData = this.$refs.imgInputData.files[0];
                console.log(this.imgInputData);
            },
            sendMessage(){
                var connectionOptions =  {
                    "force new connection" : true,
                    "reconnectionAttempts": "Infinity", //avoid having user reconnect manually in order to prevent dead clients after a server restart
                    "timeout" : 10000, //before connect_error and connect_timeout are emitted.
                    "transports" : ["websocket"]
                };
                // console.log(this.imgInputData);
                // let form = new FormData();
                // form.append('image', this.imgInputData);
                // form.append('message', this.dataInput);
                // form.append('chat_id', this.chat_channel_id);
                // axios.post('http://127.0.0.1:8000/chat/'+this.user.game_in+'-send/', form)
                // .then((response) => { 
                //     this.dataInput = '';
                //     this.imgInputData = '';
                //     this.$refs.messagesList.scrollTop = this.$refs.messagesList.scrollHeight;
                // });    
            },
            getMessages(){
            //     axios({
            //         method: 'post',
            //         url: 'http://127.0.0.1:3000/getchat-'+this.user.game_in+'/',
            //         params: { chat_id: this.chat_channel_id}
            //     })
            //     .then((response) => {   
            //         
            //         console.log(this.messages);
            //     }); 
                console.log("ffffff");
                axios.post('http://127.0.0.1:3000/getchat-'+this.user.game_in, {
                    chat_id: this.chat_channel_id
                }).then((res) => {
                    console.log(res.data)
                    this.messages = res.data;
                    this.$refs.messagesList.scrollTop = this.$refs.messagesList.scrollHeight;
                })
            },
            indexChecker(item){
                if(this.messages.indexOf(item) == 0){
                    return 1;
                }else{
                    return this.messages.indexOf(item);
                }
            },
            indexInChecker(item){
                if(this.messages.indexOf(item) == 0){
                    return -1;
                }else if(this.messages.indexOf(item) == this.messages.length-1){
                    return this.messages.indexOf(item)-1
                }else{
                    return this.messages.indexOf(item)
                } 
            },
            indexFirstChecker(item){
                
            },
            positiveRequest(){
                axios({
                    method: 'post',
                    url: 'http://127.0.0.1:8000/chat/'+this.user.game_in+'-positive/',
                    params: {chat_id: this.chat_channel_id}
                });
            },
            nagativeRequest(){
                axios({
                    method: 'post',
                    url: 'http://127.0.0.1:8000/chat/'+this.user.game_in+'-negative/',
                    params: {chat_id: this.chat_channel_id}
                });
            }
            
        }
    }
</script>

<style scoped>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,300&display=swap');

    .chat-block{
        background: #272727;
        height: 740px;
        width: 100%;
        border-radius: 15px;
        padding-top: 31px;
        padding-bottom: 20px;
        padding-left: 23px;
        padding-right: 5px;
    }

    .message-list{
        overflow: auto;
        height: 81%;
    }

    .message-list::-webkit-scrollbar-thumb{
        border-radius: 50px;
        background-color: #6A8ED4;
    }
    .message-list::-webkit-scrollbar {
        -webkit-appearance: none;
        background-color: #676767;
        width: 7px;
        border-radius: 50px;
        padding-top: 5px !important;
    }
    .message-row{
        width: 100%;
    }
    .chat-users-list{
        display: flex;
        justify-content: center;
        color: #fff;
        font-size: 14px;
    }
    .message_out{
        background: #6A8ED4;
        width: max-content;
        padding-top: 10px;
        padding-bottom: 14px;
        padding-left: 14px;
        padding-right: 23px;
        display: flex; 
        box-sizing: border-box;
        margin-bottom: 5px;
    }
    .main_message-out{
        border-radius: 0px 15px 15px 15px;
        margin-top: 15px;
    }
    .inside_out_message{
        border-radius: 15px;
    }
    .end_out_message{
        border-radius: 15px 15px 15px 0px;
    }
    .avatar-image{
        display: block;
        width: 48px;
        height: 48px;
        border-radius: 50px;
    }
    .message-struct{
        display: flex;
        flex-direction: column;
        margin-left: 9px;
        width: 100%;
    }
    .message-header{
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
    .acc-name{
        font-size: 16px;
        line-height: 19px;
        color: #fff;
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        margin: 0;
    }
    .time{
        font-size: 14px;
        margin: 0;
        color: #FFFFFF;
        font-weight: 400;
        font-family: 'Roboto', sans-serif;
        margin-left: 5px;
    }
    .message-body{
        margin-top: 7px;
    }
    .message-body p{
        max-width: 323px;
        margin: 0;
        color: #fff;
        font-size: 14px;
        line-height: 16px;
        font-weight: 400;
        font-family: 'Roboto', sans-serif;
    }
    .message_img{
        max-width: 400px;
        border-radius: 15px;
        margin-top: 15px;
    }
    .message-in-row{
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        padding-right: 10px;
    }

    .message-in{
        background: #676767;
        border-radius: 15px 0px 15px 15px;
        padding-right: 18px;
        padding-left: 24px;
        padding-bottom: 20px;
        padding-top: 18px;
        width: max-content;
    }
    .message-in p{
        max-width: 323px;
        margin: 0px;
        color: #fff;
        font-size: 14px;
        line-height: 16px;
        font-weight: 400;
        font-family: 'Roboto', sans-serif;
    }
    .time-in{
        color: #676767;
    }
    .input-data-block{
        padding-right: 17px;
    }
    .main-input{
        width: 100%;
        padding-top: 20px;
        padding-bottom: 27px;
        display: flex;
        align-items: center;
    }
    .image-input{
        display: none;
    }
    .image-label{
        width: 47px;
        height: 47px;
        background: #698DD2;
        border: none;
        border-radius: 50px;
        margin-left: 15px;
        margin-bottom: 0px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .image-label img{
        width: 22px;
        height: 22px;
    }
    .message-input{
        width: 78%;
        height: 47px;
        background: #4F4F4F;
        border: none;
        border-radius: 50px;
        padding-left: 30px;
        color: #fff;
        font-size: 14px;
        line-height: 16px;
        font-weight: 400;
        font-family: 'Roboto', sans-serif;
    }
    .main-input button{
        width: 47px;
        height: 47px;
        background: #698DD2;
        border: none;
        border-radius: 50px;
        margin-left: 15px;
    }
    .def-buttons{
        width: 100%;
        display: flex;
        justify-content: space-between;
        padding-right: 15px;
    }
    .def-buttons button{
        width: 46%;
        color: #fff;
        font-size: 14px;
        line-height: 16px;
        font-weight: 400;
        font-family: 'Roboto', sans-serif;
        height: 28px;
        border: none;
        border-radius: 50px;
        background-color:  #4F4F4F;
    }
    .service_accidense {
        width: 100%;
        display: flex;
        justify-content: center;
    }
    .service_accidense button{
        margin-top: 17px;
        color: #fff;
        font-size: 14px;
        line-height: 16px;
        font-weight: 400;
        font-family: 'Roboto', sans-serif;
        height: 28px;
        border: none;
        border-radius: 50px;
        background-color:  #282828;
    }
</style>