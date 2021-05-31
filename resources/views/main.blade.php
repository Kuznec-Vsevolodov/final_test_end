@extends('layouts.app')

@section('content')
    <div class="container">
        <section class="content-section row justify-content-center container">
        @if($_SERVER['REQUEST_URI'] == '/')
            <invite-component></invite-component>
        @elseif(preg_match('/chat/i', $_SERVER['REQUEST_URI']))
            <chat-component :chat_channel_id="{{$id}}" :user="{{Auth::user()}}"></chat-component>    
        @endif
        
        @if(Auth::check())
            <sidebar-component :user_data='{{Auth::user()}}'>
        @else
            <empty-sidebar-component></empty-sidebar-component>
        @endif    
        </section>
        @if($_SERVER['REQUEST_URI'] == '/')    
        <div class="current-chat-link">
            @if(Auth::check())
                @if(Auth::user()->current_chat != 0)
                    <a href="/chat/{{Auth::user()->current_chat}}">Ваш текущий чат</a>
                @endif
            @endif
        </div>
        @endif
    </div>    
@endsection