<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QueueSing;
use App\QueueTeam;
use App\ChatSing;
use App\ChatTeam;
use Auth;
use App\Events\QueueEvent;
use App\Events\QueueTeamEvent;

class GameController extends Controller
{
    public function singGame(Request $request){
        if(Auth::check()){
            if(Auth::user()->game_in == ''){
                if(Auth::user()->wallet >= $request->input('price')){
                    $user = Auth::user();
                    $user->wallet -= $request->input('price');
                    $user->save();
                    if (!QueueSing::where('price', $request->input('price'))->count()){
                        $name = $user->name;
                        QueueSing::create([
                            'user_1'=> $name, 
                            'user_2' => '', 
                            'price' => $request->input('price')
                        ]);
                        $id = QueueSing::where('price', $request->input('price'))->value('id');
                        
                        $user->game_in = 'sing';
                        $user->save();
                        
                        return json_encode(['queue_id' => $id]);
                    }else{
                        $database = QueueSing::where('price', $request->input('price'));
                        $sec_name = $database->value('user_1');
                        ChatSing::create([
                            'main_user' => $user->name, 
                            'secondary_user' => $sec_name
                        ]);
                        $chat = ChatSing::where('main_user', $user->name);
                        $users = [$user->name, $sec_name];
                        $user_list = "Users: ".implode(", ", $users);
        
                        $messages[] = array(
                            'author' => "NoOne",
                            'author_avatar' => false,
                            'message' => $user_list,
                            'image' => false,
                            'created' => date("H:i")
                        );
                        $chat->update(['price' => $request->input('price'), 'messages' => $messages]);
                        $chat_id = $chat->value('id');
                        $id = $database->value('id');
                        
                        event(new QueueEvent($id, $chat_id));
                        $database->delete();
                        
                        $user->game_in = 'sing';
                        $user->current_chat = $chat_id;
                        $user->save();
                        
                        return json_encode(['chat_id' => $chat_id]);
                    }
                }else{
                    return 'Ты бомж';
                }
            }else{
                return json_encode(['game_in_status' => true]);
            }
        }else{
            return json_encode(['auth_status' => false]);
        }
    }
        //return json_encode((array)QueueListSing::where('price', $request->input('price')));
        

    public function teamGame(Request $request){
        if(Auth::check()){
            if(Auth::user()->game_in == ''){
                if(Auth::user()->wallet >= $request->input('price')){
                    $user = Auth::user();
                    $user->wallet -= $request->input('price');
                    $user->save();

                    if (!QueueTeam::where('price', $request->input('price'))->count()){
                        $name = $user->name;
                        QueueTeam::create(['users'=> json_encode([$name], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK), 'price' => $request->input('price')]);
                        $id = QueueTeam::where('price', $request->input('price'))->value('id');
                        $user->game_in = 'team';
                        $user->save();
                        return json_encode(['queue_id' => $id]);
                    }else{
                        $database = QueueTeam::where('price', $request->input('price'));
                        // new event( QueueTeamEvent($database->value('id'), null));
        
                        if(count((array)json_decode($database->value('users'))) == 4){

                            $users = (array)json_decode($database->value('users'));
                            $users[] = $user->name;
                            $users_list_str = "Users: ".implode(", ", $users);
                            $message[] = array(
                                'author' => "NoOne",
                                'author_avatar' => false,
                                'message' => $users_list_str,
                                'image' => false,
                                'created' => date("H:i")
                            );

                            $chat = ChatTeam::create([
                                'main_user' => $user->name, 
                                'secondary_users' => $database->value('users'), 
                                'price' => $database->value('price')
                            ]);

                            $chat->update(['messages' => $message]);
                            $chat_id = $chat->value('id');
                            event( new QueueTeamEvent($database->value('id'), $chat_id));
                            $database->delete();
                            
                            $user->game_in = 'team';
                            $user->current_chat = $chat_id;
                            $user->save();
                            return json_encode(['chat_id' => $chat_id]);
                        }else{
                            $users = (array)json_decode($database->value('users'));
                            $users[] = $user->name;
                            $database->update([
                                'users' => json_encode($users,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK)
                            ]);
                            $id = $database->value('id');
                    
                            $user->game_in = 'team';
                            $user->save();
                            return json_encode(['queue_id' => $id]);
                        }
                    }
                }else{
                    return 'Ты бомж';
                }
            }else{
                return json_encode(['game_in_status' => true]);
            }    
        }else{
            return json_encode(['auth_status' => false]);
        }    
    }

    public function idDefender(Request $request){
        $user = Auth::user();
        $user->current_chat = $request->input('chat_id');
        $user->save();
        return 'It`s working';
    }
}
