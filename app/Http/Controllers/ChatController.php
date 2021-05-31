<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChatSing;
use App\ChatTeam;
use App\User;
use Auth;
use Image;
use App\Events\ChatEvent;
use App\Events\ChatUsersEventSing;
use App\Events\ChatUsersTeamEvent;
use App\Events\LeftedUser;

class ChatController extends Controller
{
    public function index($id){
        return view('main', ['id' => $id]);
    }

    public function getSingMessages(Request $request){
        $chat = ChatSing::where('id', $request->input('chat_id'));
        return $chat->value('messages');
    }

    public function getTeamMessages(Request $request){
        $chat = ChatTeam::where('id', $request->input('chat_id'));
        return $chat->value('messages');
    }

    public function sendSingMessage(Request $request){
        $chat = ChatSing::where('id', $request->input('chat_id'));
        $message_image;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $message_image = time().$request->input('chat_id').'.'.$image->getClientOriginalExtension();
            Image::make($image)->save(public_path('images/chats/').$message_image);
        }else{
            $message_image = false;
        }
        
        $messages = (array)json_decode($chat->value('messages'));
        $current_message = array(
            'author' => Auth::user()->name,
            'author_avatar' => Auth::user()->avatar,
            'message' => $request->input('message'),
            'image' => $message_image,
            'created' => date("H:i")
        );
        $messages[] = $current_message;
        $chat->update(['messages' => $messages]);
        event( new ChatEvent($request->input('chat_id'), $current_message, Auth::user()->game_in));
        return $current_message;
        // return $chat->value('messages');
    }    
    
    public function sendTeamMessage(Request $request){
        $chat = ChatTeam::where('id', $request->input('chat_id'));

        $message_image;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $message_image = time().$request->input('chat_id').'.'.$image->getClientOriginalExtension();
            Image::make($image)->save(public_path('images/chats/').$message_image);
        }else{
            $message_image = false;
        }

        $messages = (array)json_decode($chat->value('messages'));
        $current_message = array(
            'author' => Auth::user()->name,
            'author_avatar' => Auth::user()->avatar,
            'message' => $request->input('message'),
            'image' => $message_image,
            'created' => date("H:i")
        );
        $messages[] = $current_message;
        $chat->update(['messages' => $messages]);
        event( new ChatEvent($request->input('chat_id'), $current_message, Auth::user()->game_in));
        return $current_message;
        // return $chat->value('messages');
    }    

    public function getChatType(Request $request){
        if(Auth::user()->game_in == 'sing'){
            return 'sing';
        }else{
            return 'team';
        }
    }

    public function getSingMainUser(Request $request){
        $chat = ChatSing::where('id', $request->input('chat_id'));
        return $chat->value('main_user');
    }

    public function getTeamMainUser(Request $request){
        $chat = ChatTeam::where('id', $request->input('chat_id'));
        return $chat->value('main_user');
    }

    public function singPositiveResult(Request $request){
        $chat = ChatSing::where('id', $request->input('chat_id'));
        $looser = User::where('name', $chat->value('main_user'));
        $looser->update([ 
            'games' => $looser->value('games')+1,
            'game_in' => '',
            'current_chat' => 0
        ]);
        $winner = User::where('name', $chat->value('secondary_user'));
        $winner->update([
            'wallet' => $winner->value('wallet')+$chat->value('price')*2, 
            'games' => $winner->value('games')+1, 
            'level_status' => $winner->value('level_status')+21-$winner->value('level'),
            'game_in' => '',
            'current_chat' => 0
        ]);

        if($winner->value('level_status') >= 100){
            $winner->update([
                'level' => $winner->value('level')+1, 
                'level_status' => $winner->value('level_status') - 100]);
        }
        
        $chat->delete();
        event( new ChatUsersEventSing(true, $request->input('chat_id')));
    }

    public function singNegativeResult(Request $request){
        $chat = ChatSing::where('id', $request->input('chat_id'));
        $chat->update([
            'main_user' => $chat->value('secondary_user'), 
            'secondary_user' => $chat->value('main_user'), 
            'tries' => $chat->value('tries')+1]);
        if($chat->value('tries') == 2){
            User::where('name', $chat->value('main_user'))->update(['games' => $winner->value('games')+1]);
            User::where('name', $chat->value('secondary_user'))->update(['games' => $winner->value('games')+1]);

            $chat->delete();
            event( new ChatUsersEventSing(true, $request->input('chat_id')));
        }else{
            event( new ChatUsersEventSing(false, $request->input('chat_id')));
        }
    }
    public function teamPositiveResult(Request $request){
        $chat = ChatTeam::where('id', $request->input('chat_id'));
        $user_list = (array)json_decode($chat->value('secondary_users'));
        $reversed = false;

        if($chat->value('tries')+1 == count($user_list) || $chat->value('tries') == count($user_list)){
            $user_list = array_reverse($user_list);
            $chat->update(['round' => $chat->value('round')+1, 'tries' => 0]);
            $reversed = true;
        }

        if($chat->value('negative-status') == 1){
            $looser = User::where('name', $chat->value('main_user'));
            $looser->update([
               'games' => $looser->value('games')+1,
               'game_in' => '',
               'current_chat' => 0
            ]);
            
            $messages = (array)json_decode($chat->value('messages'));

            $message_text = $looser->value('name')." left the game";
            $current_message = array(
                'author' => "NoOne",
                'author_avatar' => false,
                'message' => $message_text,
                'image' => false,
                'created' => date("H:i")
            ); 

            $messages[] = $current_message;
            
        
            event( new ChatEvent($request->input('chat_id'), $current_message, 'team'));

            event(new LeftedUser($looser->value('name'), $chat->value('id')));

            
            if(count($user_list) == 1){
                $chat->update(['tries' => -1]);
            }
            $new_main = $user_list[$chat->value('tries')+1];
            array_splice($user_list, $chat->value('tries')+1, 1);
            $chat->update([
                'secondary_users' => json_encode($user_list, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK),
                'negative-status' => 0,
                'main_user' => $new_main,
                'messages' => $messages
            ]);
        
            if(count($user_list) == 0){
                $winner = User::where('name', $chat->value('main_user'));
                $winner->update([
                    'wallet' => $winner->value('wallet')+$chat->value('price')*5, 
                    'games' => $winner->value('games')+1, 
                    'level_status' => $winner->value('level_status')+21-$winner->value('level'),
                    'game_in' => '',
                    'current_chat' => 0
                ]);

                if($winner->value('level_status') >= 100){
                    $winner->update([
                        'level' => $winner->value('level')+1, 
                        'level_status' => $winner->value('level_status') - 100,
                        'game_in' => ''
                    ]);
                }

                event(new LeftedUser($winner->value('name'), $chat->value('id')));

                $chat->delete();
            }

            event(new ChatUsersTeamEvent($chat->value('main_user'), $chat->value('id')));
        }else{
            $current_gamer = $user_list[$chat->value('tries')];
            $user_list[$chat->value('tries')] = $chat->value('main_user');
            $chat->update([
                'main_user' => $current_gamer,
                'secondary_users' => json_encode($user_list, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK),
            ]);
            if($reversed != true){
                $chat->update(['tries' => $chat->value('tries')+1]);
            }
            $reversed = false;
            event(new ChatUsersTeamEvent($chat->value('main_user'), $chat->value('id')));
        }
    }
    public function teamNegativeResult(Request $request){
        $chat = ChatTeam::where('id', $request->input('chat_id'));
        $user_list = (array)json_decode($chat->value('secondary_users'));
        if($chat->value('tries') == count($user_list)){
            $chat->update(['tries' => 0]);
        }

        if($chat->value('negative-status') == 1){

            $looser = User::where('name', $user_list[$chat->value('tries')]);
            $looser->update([
               'games' => $looser->value('games')+1,
               'game_in' => '',
               'current_chat' => 0
            ]);

            event(new LeftedUser($looser->value('name'), $chat->value('id')));

            $user_list[$chat->value('tries')] = $chat->value('main_user');
            $new_main = $user_list[$chat->value('tries')];
            array_splice($user_list, $chat->value('tries'), 1);

            $messages = (array)json_decode($chat->value('messages'));

            $message_text = $looser->value('name')." left the game";
            $current_message = array(
                'author' => "NoOne",
                'author_avatar' => false,
                'message' => $message_text,
                'image' => false,
                'created' => date("H:i")
            ); 

            $messages[] = $current_message;

            $chat->update([
                'secondary_users' => json_encode($user_list, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK),
                'negative-status' => 0,
                'main_user' => $new_main,
                'messages' => $messages
            ]); 

            event( new ChatEvent($request->input('chat_id'), $current_message, Auth::user()->game_in));

            if(count($user_list) == 0){
                $winner = User::where('name', $chat->value('main_user'));
                $winner->update([
                    'wallet' => $winner->value('wallet')+$chat->value('price')*5, 
                    'games' => $winner->value('games')+1, 
                    'level_status' => $winner->value('level_status')+21-$winner->value('level'),
                    'game_in' => '',
                    'current_chat' => 0
                ]);

                if($winner->value('level_status') >= 100){
                    $winner->update([
                        'level' => $winner->value('level')+1, 
                        'level_status' => $winner->value('level_status') - 100,
                        'game_in' => '',
                        'current_chat' => 0
                    ]);
                }
                event(new LeftedUser($winner->value('name'), $chat->value('id')));

                $chat->delete();
            }

            event(new ChatUsersTeamEvent($chat->value('main_user'), $chat->value('id')));

        }else{
            $current_gamer = $user_list[$chat->value('tries')];
            $user_list[$chat->value('tries')] = $chat->value('main_user');
            $chat->update([
                'main_user' => $current_gamer,
                'secondary_users' => json_encode($user_list, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK),
                'negative-status' => 1,
            ]);
            event(new ChatUsersTeamEvent($chat->value('main_user'), $chat->value('id')));
        }
    }
}
