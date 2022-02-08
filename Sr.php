<?php
error_reporting(-1);
ob_start();
$API_KEY = readline ("Token : ");
define("API_KEY",$API_KEY);
function bot($SSCSY,$Syrian=[]){
$SSCSYURL = "https://api.telegram.org/bot".API_KEY."/".$SSCSY;
$curl = curl_init();
curl_setopt($curl,CURLOPT_URL,$SSCSYURL);
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_POSTFIELDS,$Syrian);
$NewBotsTele = curl_exec($curl);
curl_close($curl);
if(curl_error($curl)){
var_dump(curl_error($curl));
}else{
return json_decode($NewBotsTele);
}
}
function is_admin(string $from_id = null, string $chat_id = null, string $view=null){
$SSCSY_USER = bot('getChatMember',['chat_id'=>$chat_id,'user_id'=>$from_id])->result->status;
if($SSCSY_USER == "administrator" || $SSCSY_USER == "creator"){
if($view == null){
return true;
}else{
return $SSCSY_USER;
}
}
}
function get_info(string $chat_id = null,string $type = "member"){
if($type == "group"){
$SSCSY_INFO = bot('getChat',['chat_id'=>$chat_id])->result;
$JaberLink = !empty ($SSCSY_INFO->invite_link) ? $SSCSY_INFO->invite_link : bot('exportChatInviteLink',['chat_id'=>$chat_id])->result;
$JaberTitle = $SSCSY_INFO->title; 
$JaberId = $SSCSY_INFO->id;
$SSCSY = ['title'=>$JaberTitle,'link'=>$JaberLink,'id'=>$JaberId];
}else if ($type == "member"){
$SSCSY_INFO = bot('getChat',['chat_id'=>$chat_id])->result;
$JaberTitle = $SSCSY_INFO->first_name; 
$JaberId = $SSCSY_INFO->id;
$SSCSY = ['title'=>$JaberTitle,'id'=>$JaberId];
}
return $SSCSY;
}
function id($us_id){
global $users_json;
if(is_numeric($us_id)){
return $us_id;
} else {
return $users_json['users'][str_replace('@','',strtolower($us_id))];
}
}
function DTime ($Time){
if(preg_match('/(.*)ي/',$Time,$rr)){
$_Time = $rr[1]*24*60*60;
}elseif(preg_match('/(.*)س/',$Time,$rr)){
$_Time = $rr[1]*60*60;
}elseif(preg_match('/(.*)د/',$Time,$rr)){
$_Time = $rr[1]*60;
}
return time()+$_Time+0;
}
function T($T){
if(preg_match("/^تقييد (.*) (.*)/",$T)){
preg_match("/^تقييد (.*) (.*)/",$T,$r1);
return $r1;
}else{
preg_match("/^تقييد (.*)/",$T,$r2);
return $r2;
}}
bot('deletewebhook',[]);
function getupdate($offset){
	return bot('getupdates',[
	'offset'=>$offset,
	])->result[0];
}
$sudo = [1484504144,00,00];
function run($update){
print_r($update);
extract ($GLOBALS);
if(isset($update->message)){
@$message = $update->message;
@$message_id = $update->message->message_id;
@$username = $message->from->username;
@$chat_id = $message->chat->id;
@$title = $message->chat->title;
@$text = $message->text;
@$audio = $message->audio;
@$voice = $message->voice;
@$sticker = $message->sticker;
@$video = $message->video;
@$photo = $message->photo;
@$animation = $message->animation;
@$notice = isset($message->new_chat_member) ? $message->new_chat_member : $message->left_chat_member;
@$document = $message->document;
@$user = strtolower($message->from->username);
@$user2 = "[$user]";
@$scam = ['[','*',']','_','(',')','`','َ','ٕ','ُ','ِ','ٓ','ٓ','ٰ','ٖ','ً','ّ','ٌ','ٍ','ْ','ٔ',';'];
@$name = str_replace($scam,null,$message->from->first_name." ".$message->from->last_name);
@$from_id = $message->from->id;
@$tag = "[$name](tg://user?id=$from_id)";
@$type = $message->chat->type;
@$reply_id = $message->reply_to_message->from->id;
@$reply_name = str_replace($scam,null,$message->reply_to_message->from->first_name." ".$message->reply_to_message->from->last_name);
@$reply_user = $message->reply_to_message->from->username;
@$reply_user = "[$reply_user]";
@$reply_tag = "[$reply_name](tg://user?id=$reply_id)";
}
else if(isset($update->callback_query)){
@$data = $update->callback_query->data;
@$scam = ['[','*',']','_','(',')','`','َ','ٕ','ُ','ِ','ٓ','ٓ','ٰ','ٖ','ً','ّ','ٌ','ٍ','ْ','ٔ',';'];
@$chat_id = $update->callback_query->message->chat->id;
@$title = $update->callback_query->message->chat->title;
@$message_id = $update->callback_query->message->message_id;
@$name = str_replace($scam,null,$update->callback_query->from->first_name." ".$update->callback_query->from->last_name);
@$from_id = $update->callback_query->from->id;
@$tag = "[$name](tg://user?id=$from_id)";
@$user = $update->callback_query->from->username;
@$user2 = "[$user]";
}else if(isset($update->edited_message)){
@$message = $update->edited_message;
@$message_id = $message->message_id;
@$username = $message->from->username;
@$from_id = $message->from->id;
@$chat_id = $message->chat->id;
@$title = $message->chat->title;
@$text = $message->text;
@$audio = $message->audio;
@$voice = $message->voice;
@$sticker = $message->sticker;
@$video = $message->video;
@$photo = $message->photo;
@$animation = $message->animation;
}
#@$edit = $update->edited_message;
#@$inline = $update->message->via_bot;
#@$mark = $update->message->entities;
@$groups_json = @json_decode(file_get_contents('groups_json.json'),true);
@$users_json = @json_decode(file_get_contents('users_json.json'),true);
@$groups_txt = @file_get_contents('groups_txt.txt');
if(in_numeric($chat_id)){
$groups_json['groups'][$chat_id]['managers'] = empty ($groups_json['groups'][$chat_id]['managers']) ? [] : $groups_json['groups'][$chat_id]['managers'];
$groups_json['groups'][$chat_id]['features'] = empty ($groups_json['groups'][$chat_id]['features']) ? [] : $groups_json['groups'][$chat_id]['features'];
$groups_json['groups'][$chat_id]['silencers'] = empty ($groups_json['groups'][$chat_id]['silencers']) ? [] : $groups_json['groups'][$chat_id]['silencers'];
$groups_json["groups"][$chat_id]["ids"] = empty ($groups_json["groups"][$chat_id]["ids"]) ? [] : $groups_json["groups"][$chat_id]["ids"];
}
if(!file_exists('groups_json.json')){
file_put_contents('groups_json.json',json_encode(['run'=>'ok'],64|128|256));
}
if(!file_exists('users_json.json')){
file_put_contents('users_json.json',json_encode(['run'=>'ok'],64|128|256));
}
if(!file_exists('groups_txt.txt')){
file_put_contents('groups_txt.txt',$sudo[0]."\n");
}
$ex_txt = explode("\n",file_get_contents('groups_txt.txt'));
$sudo = [1484504144,00,00];
$array_ban = [
'بالحظر',
'بالطرد',
'بالتقييد',
'بالحذف',
'بالحذف والتقييد',
'بالحذف والطرد',
'بالحذف والحظر'
];
$d = date('D');
$channel = "[قناة السورس](https://t.me/NewBotsTele)";
$us = bot('getme',[])->result->username;
if($d == "Sat"){
unset($groups_json["spam"]["Fri"]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
}
if($d == "Sun"){
unset($groups_json["spam"]["Sat"]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
}
if($d == "Mon"){
unset($groups_json["spam"]["Sun"]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
}
if($d == "Tue"){
unset($groups_json["spam"]["Mon"]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
}
if($d == "Wed"){
unset($groups_json["spam"]["The"]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
}
if($d == "Thu"){
unset($groups_json["spam"]["Wed"]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
}
if($d == "Fri"){
unset($groups_json["spam"]["Thu"]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
}
if(isset($user) && $users_json['users'][$user] !== $from_id){
$users_json['users'][$user] = $from_id;
file_put_contents('users_json.json',json_encode($users_json,64|128|256));
}
if($text == '/start' && $type == 'private'){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"اهلا بك عزيزي : $tag

*في بوت حماية المجموعات ✅*

لتفعيل البوت اضف البوت في مجموعتك وأرسل تفعيل ..",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'أضفني إلى مجموعتك ➕','url'=>"https://t.me/".$us."?startgroup=new"]],
],
]),
]);
}
if($text !== "تفعيل" && $text !== "تفعيل البوت" && $text !== "/start@$us"){
if(!in_array($chat_id,$ex_txt)){
return 0; 
}}
if($text == "تفعيل" || $text == "تفعيل البوت" || $text == "/start@$us"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$sudo)){
if(!in_array($chat_id,$ex_txt)){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"أهلاً بك : $tag

* تم تفعيل المجموعة بنجاح ✅*

لمعرفة الاوامر ارسل كلمة `الاوامر`",
'reply_to_message_id'=>$message_id,
]);
file_put_contents('groups_txt.txt',$chat_id."\n",FILE_APPEND);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* المجموعة مفعلة مسبقاً 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر يخص المنشى الأساسي أو المطور فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "الغاء التفعيل" || $text == "الغاء تفعيل البوت"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$sudo)){
if(in_array($chat_id,$ex_txt)){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"أهلاً بك : $tag

* تم الغاء تفعيل المجموعة بنجاح ✅*",
'reply_to_message_id'=>$message_id,
]);
$str = str_replace("$chat_id\n",null,$groups_txt);
file_put_contents('groups_txt.txt',$str);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* المجموعة غير مفعلة مسبقاً 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر يخص المنشى الأساسي أو المطور فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(preg_match('/(.*) @(.*)/',$text,$tu)){
if(!id($tu[2])){
return 0;
}}
if(!is_admin($from_id,$chat_id) && !in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if(!$notice && $message &&  $groups_json['groups'][$chat_id]['setting']['chat'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if($photo &&  $groups_json['groups'][$chat_id]['setting']['photo'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if($video &&  $groups_json['groups'][$chat_id]['setting']['video'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if($notice &&  $groups_json['groups'][$chat_id]['setting']['notices'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if(mb_strlen($text) > 750 &&  $groups_json['groups'][$chat_id]['setting']['texts'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if($sticker &&  $groups_json['groups'][$chat_id]['setting']['sticker'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if($voice &&  $groups_json['groups'][$chat_id]['setting']['voice'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if($animation &&  $groups_json['groups'][$chat_id]['setting']['animation'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if($sticker &&  $groups_json['groups'][$chat_id]['setting']['sticker'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if($audio &&  $groups_json['groups'][$chat_id]['setting']['audio'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if($document &&  $groups_json['groups'][$chat_id]['setting']['document'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if($message->forward_from || $message->forward_from_chat){
if($groups_json['groups'][$chat_id]['setting']['forward'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}}
if($mark &&  $groups_json['groups'][$chat_id]['setting']['mark'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if($inline &&  $groups_json['groups'][$chat_id]['setting']['inline'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if($text !== "@admin" && preg_match('/@(.*)/',$text) && $groups_json['groups'][$chat_id]['setting']['users'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if($update->message->contact &&  $groups_json['groups'][$chat_id]['setting']['contect'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if(!in_array(id($matches[1]),$groups_json['groups'][$chat_id]['features'])){
if(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$text) && $groups_json['groups'][$chat_id]['setting']['link'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}}
if($message->sender_chat->type == "channel" && $groups_json['groups'][$chat_id]['setting']['channels'] == "no"){
bot('deletemessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
bot('banChatSenderChat',[
'chat_id'=>$chat_id,
'sender_chat_id'=>$message->sender_chat->id
]);
}
if($edit &&  $groups_json['groups'][$chat_id]['setting']['edit'] == "no"){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
}

$ar = ['الاشعارات','الصور','الفيديوهات','الصوتيات','المقاطع الصوتية','القنوات','الدردشة','الكل'
,'جهات الاتصال','الملصقات','الرابط','الملفات','المتحركات','التعديل','الانلاين','المعرفات','الماركداون','التوجيه','الرسائل المزعجة'];
$en = ['notices','photo','video','voice','audio','channels','chat','all','contect','sticker','link','document','animation','edit','inline','users','mark','forward','texts'];
if(!$groups_json['groups'][$chat_id]['setting'] && is_numeric($chat_id)){
$groups_json['groups'][$chat_id]['setting']['photo'] = 
$groups_json['groups'][$chat_id]['setting']['video'] = 
$groups_json['groups'][$chat_id]['setting']['contect'] = 
$groups_json['groups'][$chat_id]['setting']['voice'] = 
$groups_json['groups'][$chat_id]['setting']['audio'] = 
$groups_json['groups'][$chat_id]['setting']['channels'] = 
$groups_json['groups'][$chat_id]['setting']['animation']
= $groups_json['groups'][$chat_id]['setting']['chat'] = 
$groups_json['groups'][$chat_id]['setting']['notices'] = 
$groups_json['groups'][$chat_id]['setting']['all'] = 
$groups_json['groups'][$chat_id]['setting']['sticker'] = 
$groups_json['groups'][$chat_id]['setting']['link'] = 
$groups_json['groups'][$chat_id]['setting']['document'] = 
$groups_json['groups'][$chat_id]['setting']['inline'] = 
$groups_json['groups'][$chat_id]['setting']['edit'] = 
$groups_json['groups'][$chat_id]['setting']['mark'] = 
$groups_json['groups'][$chat_id]['setting']['users'] = 
$groups_json['groups'][$chat_id]['setting']['forward'] = 
$groups_json['groups'][$chat_id]['setting']['texts'] = 
"yes";
$groups_json["groups"][$chat_id]["acs"]["spam"] = "10";
$groups_json["groups"][$chat_id]["acs"]["typespam"] = "بالحذف والتقييد";
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
}
if(preg_match("/^كتم (.*)/",$text,$matches) && !is_admin(id($matches[1]),$chat_id) && !in_array(id($matches[1]),$groups_json['groups'][$chat_id]['managers']) && !in_array(id($matches[1]),$groups_json['groups'][$chat_id]['admins'])){
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers']) || in_array($from_id,$groups_json['groups'][$chat_id]['admins'])){
if(!in_array(id($matches[1]),$groups_json['groups'][$chat_id]['silencers'])){
$geinfo = get_info(id($matches[1]),"member")['title'];
$tg = "tg://user?id=".id($matches[1]);
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>" المستخدم : [$geinfo]($tg)

*تم كتمه بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
$groups_json['groups'][$chat_id]['silencers'][] = id($matches[1]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا العضو مكتوم مسبقاً 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(isset($reply_id) && $text == "كتم" && !is_admin($reply_id,$chat_id) && !in_array($reply_id,$groups_json['groups'][$chat_id]['managers']) && !in_array($reply_id,$groups_json['groups'][$chat_id]['admins'])){
if(!in_array($reply_id,$groups_json['groups'][$chat_id]['silencers'])){
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers']) || in_array($from_id,$groups_json['groups'][$chat_id]['admins'])){
$tg = "tg://user?id=".$reply_id;
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>" المستخدم : $reply_tag

*تم كتمه بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
$groups_json['groups'][$chat_id]['silencers'][] = $reply_id;
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا العضو مكتوم مسبقاً 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($message && in_array($from_id,$groups_json['groups'][$chat_id]['silencers'])){
bot('deletemessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
return 0;
}
if(preg_match("/^الغاء الكتم (.*)/",$text,$matches) && !is_admin(id($matches[1]),$chat_id) && !in_array(id($matches[1]),$groups_json['groups'][$chat_id]['managers']) && !in_array(id($matches[1]),$groups_json['groups'][$chat_id]['admins'])){
if(in_array(id($matches[1]),$groups_json['groups'][$chat_id]['silencers'])){
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers']) || in_array($from_id,$groups_json['groups'][$chat_id]['admins'])){
$geinfo = get_info(id($matches[1]),"member")['title'];
$tg = "tg://user?id=".id($matches[1]);
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>" المستخدم : [$geinfo]($tg)

*تم الغاء كتمه بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
unset($groups_json['groups'][$chat_id]['silencers'][array_search(id($matches[1]),$groups_json['groups'][$chat_id]['silencers'])]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا العضو غير مكتوم مسبقاً 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(isset($reply_id) && $text == "الغاء الكتم"){
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers']) || in_array($from_id,$groups_json['groups'][$chat_id]['admins'])){
if(in_array($reply_id,$groups_json['groups'][$chat_id]['silencers'])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>" المستخدم : $reply_tag

*تم الغاء كتمه بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
unset($groups_json['groups'][$chat_id]['silencers'][array_search($reply_id,$groups_json['groups'][$chat_id]['silencers'])]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا العضو غير مكتوم مسبقاً 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}

if(preg_match("/^تقييد (.*)/",$text) && empty($reply_id)){
$matches=[];
$matches = T($text);
if(!is_admin(id($matches[1]),$chat_id) && !in_array(id($matches[1]),$groups_json['groups'][$chat_id]['managers']) && !in_array(id($matches[1]),$groups_json['groups'][$chat_id]['admins'])){
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers']) || in_array($from_id,$groups_json['groups'][$chat_id]['admins'])){
$TimeD = !empty (str_replace("تقييد $matches[1]",null,$text)) ? str_replace("تقييد $matches[1]",null,$text) : '';
$TimeT = !empty (str_replace("تقييد $matches[1]",null,$text)) ? 'لمدة'.$TimeD : '';
$geinfo = get_info(id($matches[1]),"member")['title'];
$tg = "tg://user?id=".id($matches[1]);
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : [$geinfo]($tg) 

*تم تقييده بنجاح $TimeT ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
if(!isset($TimeD)){
bot('restrictChatMember',[
'user_id'=>id($matches[1]),   
   'chat_id'=>$chat_id,
  'can_post_messages'=>false,
]);
}else{
bot('restrictChatMember',[
'user_id'=>id($matches[1]),   
   'chat_id'=>$chat_id,
  'can_post_messages'=>false,
'until_date'=>DTime($TimeD),
]);
}
$groups_json['groups'][$chat_id]['enrollers'][] = id($matches[1]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}}
if(preg_match("/^تقييد/",$text) && isset($reply_id)){
$matches=[];
if($text == "تقييد"){
$TimeD = $TimeT = null;
}else{
preg_match("/^تقييد (.*)/",$text,$matches);
$TimeD = $matches[1];
$TimeT = "لمدة ".$matches[1];
}
if(!is_admin($reply_id,$chat_id) && !in_array($reply_id,$groups_json['groups'][$chat_id]['managers']) && !in_array($reply_id,$groups_json['groups'][$chat_id]['admins'])){
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers']) || in_array($from_id,$groups_json['groups'][$chat_id]['admins'])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : $reply_tag

*تم تقييده بنجاح $TimeT ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
if(!isset($TimeD)){
bot('restrictChatMember',[
'user_id'=>$reply_id,   
   'chat_id'=>$chat_id,
  'can_post_messages'=>false,
]);
}else{
bot('restrictChatMember',[
'user_id'=>$reply_id,   
   'chat_id'=>$chat_id,
  'can_post_messages'=>false,
'until_date'=>DTime($TimeD),
]);
}
$groups_json['groups'][$chat_id]['enrollers'][] = $reply_id;
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}}
if(preg_match("/^فك التقييد (.*)/",$text,$matches)){
if(!is_admin(id($matches[1]),$chat_id) && !in_array(id($matches[1]),$groups_json['groups'][$chat_id]['managers']) && !in_array(id($matches[1]),$groups_json['groups'][$chat_id]['admins'])){
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers']) || in_array($from_id,$groups_json['groups'][$chat_id]['admins'])){
$geinfo = get_info(id($matches[1]),"member")['title'];
$tg = "tg://user?id=".id($matches[1]);
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : [$geinfo]($tg) 

*تم فك تقييده بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
bot('restrictChatMember',[
'chat_id'=>$chat_id,
'user_id'=>id($matches[1]),
'can_post_messages'=>true,
'can_add_web_page_previews'=>false,
'can_send_other_messages'=>true,
'can_send_media_messages'=>true,
]);
unset($groups_json['groups'][$chat_id]['enrollers'][array_search(id($matches[1]),$groups_json['groups'][$chat_id]['enrollers'])]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}}
if($text == "فك التقييد" && isset($reply_id)){
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers']) || in_array($from_id,$groups_json['groups'][$chat_id]['admins'])){
if(!is_admin($reply_id,$chat_id) && !in_array($reply_id,$groups_json['groups'][$chat_id]['managers']) && !in_array($reply_id,$groups_json['groups'][$chat_id]['admins'])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : $reply_tag

*تم فك تقييده بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
bot('restrictChatMember',[
'chat_id'=>$chat_id,
'user_id'=>$reply_id,
'can_post_messages'=>true,
'can_add_web_page_previews'=>false,
'can_send_other_messages'=>true,
'can_send_media_messages'=>true,
]);
unset($groups_json['groups'][$chat_id]['enrollers'][array_search($reply_id,$groups_json['groups'][$chat_id]['enrollers'])]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "رفع مدير" && isset($reply_id)){
if(is_admin($from_id,$chat_id,"view") == "creator"){
if(!in_array($reply_id,$groups_json['groups'][$chat_id]['managers'])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : $reply_tag

*تم رفعه مدير بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
$groups_json['groups'][$chat_id]['managers'][] = $reply_id;
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*تم رفع هذا العضو مدير مسبقاً 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(preg_match("/^رفع مدير (.*)/",$text,$matches)){
if(is_admin($from_id,$chat_id,"view") == "creator"){
if(!in_array(id($matches[1]),$groups_json['groups'][$chat_id]['managers'])){
$geinfo = get_info(id($matches[1]),"member")['title'];
$tg = "tg://user?id=".id($matches[1]);
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : [$geinfo]($tg)

*تم رفعه مدير بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
$groups_json['groups'][$chat_id]['managers'][] = id($matches[1]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*تم رفع هذا العضو مدير مسبقاً 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "تنزيل مدير" && isset($reply_id)){
if(is_admin($from_id,$chat_id,"view") == "creator"){
if(in_array($reply_id,$groups_json['groups'][$chat_id]['managers'])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : $reply_tag

*تم تنزيله من المدير بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
unset($groups_json['groups'][$chat_id]['managers'][array_search($reply_id,$groups_json['groups'][$chat_id]['managers'])]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا العضو ليس مدير مسبقاً 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(preg_match("/^تنزيل مدير (.*)/",$text,$matches)){
if(is_admin($from_id,$chat_id,"view") == "creator"){
if(in_array(id($matches[1]),$groups_json['groups'][$chat_id]['managers'])){
$geinfo = get_info(id($matches[1]),"member")['title'];
$tg = "tg://user?id=".id($matches[1]);
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : [$geinfo]($tg)

*تم تنزيله من المدير بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
unset($groups_json['groups'][$chat_id]['managers'][array_search(id($matches[1]),$groups_json['groups'][$chat_id]['managers'])]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا العضو ليس مدير مسبقاً 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "المكتومين"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if(count ($groups_json['groups'][$chat_id]['silencers']) !== 0){
foreach ($groups_json['groups'][$chat_id]['silencers'] as $silencer){
$get_info = get_info($silencer,"member")['title'];
$mem .="[$get_info](tg://user?id=$silencer)\n";
}
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*المكتومين : *
$mem",
'reply_to_message_id'=>$message_id,
]);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*لا يوجد مكتومين 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المدير أو المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "مسح المكتومين"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if(count ($groups_json['groups'][$chat_id]['silencers']) !== 0){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*تم مسح المكتومين بنجاح ✅* 

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
unset($groups_json['groups'][$chat_id]['silencers']);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*لا يوجد مكتومين 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المدير أو المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "مسح المقيدين"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if(count ($groups_json['groups'][$chat_id]['enrollers']) !== 0){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*تم مسح المقيدين بنجاح ✅* 

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
foreach ($groups_json['groups'][$chat_id]['enrollers'] as $enroller){
bot('restrictChatMember',[
'chat_id'=>$chat_id,
'user_id'=>$enroller,
'can_post_messages'=>true,
'can_add_web_page_previews'=>false,
'can_send_other_messages'=>true,
'can_send_media_messages'=>true,
]);
}
unset($groups_json['groups'][$chat_id]['enrollers']);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*لا يوجد مقيدين 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المدير أو المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "المقيدين"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if(count ($groups_json['groups'][$chat_id]['enrollers']) !== 0){
foreach ($groups_json['groups'][$chat_id]['enrollers'] as $enroller){
$get_info = get_info($enroller,"member")['title'];
$mem .="[$get_info](tg://user?id=$enroller)\n";
}
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*المقيدين : *
$mem",
'reply_to_message_id'=>$message_id,
]);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*لا يوجد مقيدين 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المدير أو المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(preg_match("/^طرد (.*)/",$text,$matches) && !is_admin(id($matches[1]),$chat_id) && !in_array(id($matches[1]),$groups_json['groups'][$chat_id]['managers']) && !in_array(id($matches[1]),$groups_json['groups'][$chat_id]['admins'])){
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers']) || in_array($from_id,$groups_json['groups'][$chat_id]['admins'])){
if(!in_array(id($matches[1]),$groups_json['groups'][$chat_id]['expelleres'])){
$geinfo = get_info(id($matches[1]),"member")['title'];
$tg = "tg://user?id=".id($matches[1]);
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>" المستخدم : [$geinfo]($tg)

*تم طرده بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
$groups_json['groups'][$chat_id]['expelleres'][] = id($matches[1]);
bot('KickChatMember',[
'chat_id'=>$chat_id,
'user_id'=>id($matches[1]),
]);
bot('UnbanChatmember',[
'chat_id'=>$chat_id,
'user_id'=>id($matches[1]),
]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا العضو مطرود مسبقاً 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(isset($reply_id) && $text == "طرد" && !is_admin($reply_id,$chat_id) && !in_array($reply_id,$groups_json['groups'][$chat_id]['managers']) && !in_array($reply_id,$groups_json['groups'][$chat_id]['admins'])){
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers']) || in_array($from_id,$groups_json['groups'][$chat_id]['admins'])){
if(!in_array($reply_id,$groups_json['groups'][$chat_id]['expelleres'])){
$tg = "tg://user?id=".$reply_id;
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>" المستخدم : $reply_tag

*تم طرده بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
$groups_json['groups'][$chat_id]['expelleres'][] = $reply_id;
bot('KickChatMember',[
'chat_id'=>$chat_id,
'user_id'=>$reply_id,
]);
bot('UnbanChatmember',[
'chat_id'=>$chat_id,
'user_id'=>$reply_id,
]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا العضو مطرود مسبقاً 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "مسح المطرودين"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if(count ($groups_json['groups'][$chat_id]['expelleres']) !== 0){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*تم مسح المطرودين بنجاح ✅* 

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
unset($groups_json['groups'][$chat_id]['expelleres']);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*لا يوجد مطرودين 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المدير أو المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "المطرودين"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if(count ($groups_json['groups'][$chat_id]['expelleres']) !== 0){
foreach ($groups_json['groups'][$chat_id]['expelleres'] as $expeller){
$get_info = get_info($expeller,"member")['title'];
$mem .="[$get_info](tg://user?id=$expeller)\n";
}
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*المطرودين : *
$mem",
'reply_to_message_id'=>$message_id,
]);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*لا يوجد مطرودين 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المدير أو المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(preg_match("/^حظر (.*)/",$text,$matches) && !is_admin(id($matches[1]),$chat_id) && !in_array(id($matches[1]),$groups_json['groups'][$chat_id]['managers']) && !in_array(id($matches[1]),$groups_json['groups'][$chat_id]['admins'])){
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers']) || in_array($from_id,$groups_json['groups'][$chat_id]['admins'])){
if(!in_array(id($matches[1]),$groups_json['groups'][$chat_id]['baners'])){
$geinfo = get_info(id($matches[1]),"member")['title'];
$tg = "tg://user?id=".id($matches[1]);
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>" المستخدم : [$geinfo]($tg)

*تم حظره بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
$groups_json['groups'][$chat_id]['baners'][] = id($matches[1]);
bot('KickChatMember',[
'chat_id'=>$chat_id,
'user_id'=>id($matches[1]),
]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا العضو محظور مسبقاً 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(isset($reply_id) && $text == "حظر" && !is_admin($reply_id,$chat_id) && !in_array($reply_id,$groups_json['groups'][$chat_id]['managers']) && !in_array($reply_id,$groups_json['groups'][$chat_id]['admins'])){
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers']) || in_array($from_id,$groups_json['groups'][$chat_id]['admins'])){
if(!in_array($reply_id,$groups_json['groups'][$chat_id]['baners'])){
$tg = "tg://user?id=".$reply_id;
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>" المستخدم : $reply_tag

*تم حظره بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
$groups_json['groups'][$chat_id]['baners'][] = $reply_id;
bot('KickChatMember',[
'chat_id'=>$chat_id,
'user_id'=>$reply_id,
]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا العضو محظور مسبقاً 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "مسح المحظورين"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if(count ($groups_json['groups'][$chat_id]['baners']) !== 0){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*تم مسح المحظورين بنجاح ✅* 

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
foreach($groups_json['groups'][$chat_id]['baners'] as $baner){
bot('UnbanChatmember',[
'chat_id'=>$chat_id,
'user_id'=>$baner,
]);
}
unset($groups_json['groups'][$chat_id]['baners']);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*لا يوجد محظورين 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المدير أو المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(preg_match("/^الغاء الحظر (.*)/",$text,$matches) && !is_admin(id($matches[1]),$chat_id) && !in_array(id($matches[1]),$groups_json['groups'][$chat_id]['managers']) && !in_array(id($matches[1]),$groups_json['groups'][$chat_id]['admins'])){
if(in_array(id($matches[1]),$groups_json['groups'][$chat_id]['baners'])){
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers']) || in_array($from_id,$groups_json['groups'][$chat_id]['admins'])){
$geinfo = get_info(id($matches[1]),"member")['title'];
$tg = "tg://user?id=".id($matches[1]);
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>" المستخدم : [$geinfo]($tg)

*تم الغاء حظره بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
unset($groups_json['groups'][$chat_id]['baners'][array_search(id($matches[1]),$groups_json['groups'][$chat_id]['baners'])]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا العضو غير محظور مسبقاً 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(isset($reply_id) && $text == "الغاء الحظر"){
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers']) || in_array($from_id,$groups_json['groups'][$chat_id]['admins'])){
if(in_array($reply_id,$groups_json['groups'][$chat_id]['baners'])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>" المستخدم : $reply_tag

*تم الغاء حظره بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
unset($groups_json['groups'][$chat_id]['baners'][array_search($reply_id,$groups_json['groups'][$chat_id]['baners'])]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا العضو غير محظور مسبقاً 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "المحظورين"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if(count ($groups_json['groups'][$chat_id]['baners']) !== 0){
foreach ($groups_json['groups'][$chat_id]['baners'] as $baner){
$get_info = get_info($baner,"member")['title'];
$mem .="[$get_info](tg://user?id=$baner)\n";
}
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*المحظورين : *
$mem",
'reply_to_message_id'=>$message_id,
]);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*لا يوجد محظورين 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المدير أو المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "تاك"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
$k = $num = 0;
$r = bot('sendmessage',['chat_id'=>$chat_id,'parse_mode'=>"MarkDown",'text'=>"*جاري عمل تاك ....*",'reply_to_message_id'=>$message_id,])->result->message_id;
foreach ($groups_json['groups'][$chat_id]['ids'] as $id){
$k++;
if($k == 100){
$k = 0;
$num += 1;
}
$geinfo = get_info($id,"member")['title'];
$tg = "tg://user?id=".$id;
if(isset($id) && ! empty ($id)){
$tagall[$num] .= "[$geinfo]($tg)" ."\n";
}
}
bot('deletemessage',[
'chat_id'=>$chat_id,
'message_id'=>$r,
]);
foreach ($tagall as $send){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>$send,
'reply_to_message_id'=>$message_id,
]);
}
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المدير أو المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "مسح الميديا"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
$n = 0;
foreach($groups_json['groups'][$chat_id]['media'] as $med){
$n++;
bot('deletemessage',[
'chat_id'=>$chat_id,
'message_id'=>$med,
]);
unset($groups_json['groups'][$chat_id]['media'][array_search($med,$groups_json['groups'][$chat_id]['media'])]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
if($n == 100 || count($groups_json['groups'][$chat_id]['media']) == 0){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*تم مسح الميديا بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
break;
}
}
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المدير أو المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}

if(preg_match('/^قفل (.*)/',$text,$match) && in_array($match[1],$ar)){
$array = array_combine($ar,$en);
if(!$array[$match[1]]) return 0;
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if($groups_json['groups'][$chat_id]['setting'][$array[$match[1]]] == "yes"){
$txt = "*تم قفل $match[1] بنجاح ✅*";
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"$txt 

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
if($match[1] == "الكل"){
$groups_json['groups'][$chat_id]['setting']['photo'] = 
$groups_json['groups'][$chat_id]['setting']['video'] = 
$groups_json['groups'][$chat_id]['setting']['contect'] = 
$groups_json['groups'][$chat_id]['setting']['voice'] = 
$groups_json['groups'][$chat_id]['setting']['audio'] = 
$groups_json['groups'][$chat_id]['setting']['channels'] = 
$groups_json['groups'][$chat_id]['setting']['animation']
= $groups_json['groups'][$chat_id]['setting']['chat'] = 
$groups_json['groups'][$chat_id]['setting']['notices'] = 
$groups_json['groups'][$chat_id]['setting']['all'] = 
$groups_json['groups'][$chat_id]['setting']['sticker'] = 
$groups_json['groups'][$chat_id]['setting']['link'] = 
$groups_json['groups'][$chat_id]['setting']['document'] = 
$groups_json['groups'][$chat_id]['setting']['inline'] = 
$groups_json['groups'][$chat_id]['setting']['edit'] = 
$groups_json['groups'][$chat_id]['setting']['mark'] = 
$groups_json['groups'][$chat_id]['setting']['users'] = 
$groups_json['groups'][$chat_id]['setting']['forward'] = 
$groups_json['groups'][$chat_id]['setting']['texts'] = 
"no";
}
$groups_json['groups'][$chat_id]['setting'][$array[$match[1]]] = "no";
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*$match[1] مقفولة بالفعل 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(preg_match('/^فتح (.*)/',$text,$match) && in_array($match[1],$ar)){
$array = array_combine($ar,$en);
if(!$array[$match[1]]) return 0;
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if($groups_json['groups'][$chat_id]['setting'][$array[$match[1]]] == "no"){
$txt = "*تم فتح $match[1] بنجاح ✅*";
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"$txt 

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
if($match[1] == "الكل"){
$groups_json['groups'][$chat_id]['setting']['photo'] = 
$groups_json['groups'][$chat_id]['setting']['video'] = 
$groups_json['groups'][$chat_id]['setting']['contect'] = 
$groups_json['groups'][$chat_id]['setting']['voice'] = 
$groups_json['groups'][$chat_id]['setting']['audio'] = 
$groups_json['groups'][$chat_id]['setting']['channels'] = 
$groups_json['groups'][$chat_id]['setting']['animation']
= $groups_json['groups'][$chat_id]['setting']['chat'] = 
$groups_json['groups'][$chat_id]['setting']['notices'] = 
$groups_json['groups'][$chat_id]['setting']['all'] = 
$groups_json['groups'][$chat_id]['setting']['sticker'] = 
$groups_json['groups'][$chat_id]['setting']['link'] = 
$groups_json['groups'][$chat_id]['setting']['document'] = 
$groups_json['groups'][$chat_id]['setting']['inline'] = 
$groups_json['groups'][$chat_id]['setting']['edit'] = 
$groups_json['groups'][$chat_id]['setting']['mark'] = 
$groups_json['groups'][$chat_id]['setting']['users'] = 
$groups_json['groups'][$chat_id]['setting']['forward'] = 
$groups_json['groups'][$chat_id]['setting']['texts'] = 
"yes";
}
$groups_json['groups'][$chat_id]['setting'][$array[$match[1]]] = "yes";
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*$match[1] مفتوحة بالفعل 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"* هذا الأمر مخصص فقط للادمن 💢*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}

if($text == "اضف رد"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"
عزيزي : $tag ⭐

*حسناً قم بإرسال كلمة الرد ✅*",
'reply_to_message_id'=>$message_id,
]);
$groups_json['chats'][$chat_id][$from_id]['ac'] = "send-text";
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المدير أو المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text && $groups_json['chats'][$chat_id][$from_id]['ac'] == "send-text"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
unset($groups_json['chats'][$chat_id][$from_id]['ac']);
$groups_json['chats'][$chat_id][$from_id]['ac'] = "send-reply";
$groups_json['chats'][$chat_id][$from_id]['send-text'] = $text;
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"
عزيزي : $tag ⭐

*حسناً قم بإرسال الرد ✅*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المدير أو المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text && $groups_json['chats'][$chat_id][$from_id]['ac'] == "send-reply"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
$groups_json['replys'][$chat_id][$groups_json['chats'][$chat_id][$from_id]['send-text']] = $text;
unset($groups_json['chats'][$chat_id][$from_id]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"
عزيزي : $tag ⭐

*تم الحفظ بنجاح ✅*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المدير أو المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "حذف رد"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"
عزيزي : $tag ⭐

*حسناً قم بإرسال كلمة الرد لحذفها ✅*",
'reply_to_message_id'=>$message_id,
]);
$groups_json['chats'][$chat_id][$from_id]['ac'] = "del-text";
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المدير أو المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text && $groups_json['chats'][$chat_id][$from_id]['ac'] == "del-text"){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
unset($groups_json['chats'][$chat_id][$from_id]);
if(isset($groups_json['replys'][$chat_id][$text])){
unset($groups_json['replys'][$chat_id][$text]);
$txt = "*تم الحذف بنجاح ✅*";
}else{
$txt = "*لا يوجد هكذا رد 🔴*";
}
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"
عزيزي : $tag ⭐

$txt",
'reply_to_message_id'=>$message_id,
]);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المدير أو المنشى الاساسي فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "رفع ادمن" && isset($reply_id)){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if(!is_admin($reply_id,$chat_id)){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : $reply_tag

*تم رفعه ادمن بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
bot('promoteChatMember',[
'chat_id'=>$chat_id,
'user_id'=>$reply_id,
'can_restrict_members'=>true,
'can_change_info'=>true,
'can_delete_messages'=>true,
'can_pin_messages'=>true,
]);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*تم رفع هذا العضو مدير مسبقاً 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المنشى الاساسي والمدير فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(preg_match("/^رفع ادمن (.*)/",$text,$matches)){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if(!is_admin(id($matches[1]),$chat_id)){
$geinfo = get_info(id($matches[1]),"member")['title'];
$tg = "tg://user?id=".id($matches[1]);
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : [$geinfo]($tg)

*تم رفعه ادمن بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
bot('promoteChatMember',[
'chat_id'=>$chat_id,
'user_id'=>id($matches[1]),
'can_restrict_members'=>true,
'can_change_info'=>true,
'can_delete_messages'=>true,
'can_pin_messages'=>true,
]);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*تم رفع هذا العضو ادمن مسبقاً 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المنشى الاساسي والمدير فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "تنزيل ادمن" && isset($reply_id)){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if(is_admin($reply_id,$chat_id)){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : $reply_tag

*تم تنزيله من الادمن بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
bot('promoteChatMember',[
'chat_id'=>$chat_id,
'user_id'=>$reply_id,
'can_restrict_members'=>false,
'can_change_info'=>false,
'can_pin_messages'=>false,
]);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا العضو ليس ادمن مسبقاً 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المنشى الاساسي والمدير فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(preg_match("/^تعيين التكرار (.*)$/",$text)){
preg_match("/^تعيين التكرار (.*)$/",$text,$m);
$nt = $m[1];
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*تم تعيين عدد مرات التكرار $nt بنجاح ✅*

بواسطة : $tag",
"reply_to_message_id"=>$message_id,
]);
$groups_json["groups"][$chat_id]["acs"]["spam"] = $nt;
file_put_contents("groups_json.json",json_encode($groups_json,64|128|256));
return 0;
}else{
bot("sendmessage",[
"chat_id"=>$chat_id,
"parse_mode"=>"MarkDown",
"text"=>"* هذا الأمر مخصص فقط للادمن 💢*",
"reply_to_message_id"=>$message_id,
]);
return 0;
}}
if(preg_match("/^تنزيل ادمن (.*)/",$text,$matches)){
if(is_admin($from_id,$chat_id,"view") == "creator" || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if(is_admin(id($matches[1]),$chat_id)){
$geinfo = get_info(id($matches[1]),"member")['title'];
$tg = "tg://user?id=".id($matches[1]);
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : [$geinfo]($tg)

*تم تنزيله من الادمن بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
bot('promoteChatMember',[
'chat_id'=>$chat_id,
'user_id'=>id($matches[1]),
'can_restrict_members'=>false,
'can_change_info'=>false,
'can_pin_messages'=>false,
]);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا العضو ليس ادمن مسبقاً 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص المنشى الاساسي والمدير فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text && isset($groups_json['replys'][$chat_id][$text])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>$groups_json['replys'][$chat_id][$text],
'reply_to_message_id'=>$message_id,
]);
}
if(preg_match("/قفل التكرار (.*)/",$text)){
preg_match("/قفل التكرار (.*)/",$text,$m);
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if(in_array($m[1],$array_ban)){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"
*تم $text بنجاح ✅*

بواسطة : $tag",
"reply_to_message_id"=>$message_id,
]);
unset($groups_json["spam"][$d][$chat_id]);
$groups_json["groups"][$chat_id]["acs"]["typespam"] = $m[1];
file_put_contents("groups_json.json",json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"لم يتم العثور على العقوبة *$m[1]* 

تجنب الأخطاء الإملائية ..",
"reply_to_message_id"=>$message_id,
]);
return 0;
}
}else{
bot("sendmessage",[
"chat_id"=>$chat_id,
"parse_mode"=>"MarkDown",
"text"=>"* هذا الأمر مخصص فقط للادمن 💢*",
"reply_to_message_id"=>$message_id,
]);
return 0;
}}
if($text == "فتح التكرار"){
if(is_admin($from_id,$chat_id) || in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
if($groups_json["groups"][$chat_id]["acs"]["typespam"] !== "no"){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"
*تم فتح التكرار بنجاح ✅*

بواسطة : $tag",
"reply_to_message_id"=>$message_id,
]);
unset($groups_json["spam"][$d][$chat_id]);
$groups_json["groups"][$chat_id]["acs"]["typespam"] = "no";
file_put_contents("groups_json.json",json_encode($groups_json,64|128|256));
return 0;
}else{
bot("sendmessage",[
"chat_id"=>$chat_id,
"parse_mode"=>"MarkDown",
"text"=>"*التكرار مفتوح بالفعل 🔴*",
"reply_to_message_id"=>$message_id,
]);
return 0;
}}else{
bot("sendmessage",[
"chat_id"=>$chat_id,
"parse_mode"=>"MarkDown",
"text"=>"* هذا الأمر مخصص فقط للادمن 💢*",
"reply_to_message_id"=>$message_id,
]);
return 0;
}}
if($groups_json["spam"][$d][$chat_id][$from_id] >= $groups_json["groups"][$chat_id]["acs"]["spam"]){
if(!is_admin($from_id,$chat_id) && !in_array($from_id,$groups_json['groups'][$chat_id]['managers']) && !in_array($from_id,$groups_json['groups'][$chat_id]['features'])){
if(preg_match("/تقييد/",$groups_json["groups"][$chat_id]["acs"]["typespam"])){
bot('restrictChatMember',[
'chat_id'=>$chat_id,
'user_id'=>$from_id,   
'can_post_messages'=>false,
]);
unset($groups_json["spam"][$d][$chat_id][$from_id]);
$groups_json['groups'][$chat_id]['enrollers'][] = $from_id;
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
}
if(preg_match("/حذف/",$groups_json["groups"][$chat_id]["acs"]["typespam"])){
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
if(preg_match("/كتم/",$groups_json["groups"][$chat_id]["acs"]["typespam"])){
$groups_json['groups'][$chat_id]['silencers'][] = $from_id;
unset($groups_json["spam"][$d][$chat_id][$from_id]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
}
if(preg_match("/طرد/",$groups_json["groups"][$chat_id]["acs"]["typespam"])){
bot('KickChatMember',[
'chat_id'=>$chat_id,
'user_id'=>$from_id,
]);
bot('UnbanChatmember',[
'chat_id'=>$chat_id,
'user_id'=>$from_id,
]);
unset($groups_json["spam"][$d][$chat_id][$from_id]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
}
if(preg_match("/حظر/",$groups_json["groups"][$chat_id]["acs"]["typespam"])){
bot('KickChatMember',[
'chat_id'=>$chat_id,
'user_id'=>$from_id,
]);
unset($groups_json["spam"][$d][$chat_id][$from_id]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
}
}}
if($text == "رفع مميز" && isset($reply_id)){
if(is_admin($from_id,$chat_id) || in_array($reply_id,$groups_json['groups'][$chat_id]['managers'])){
if(!in_array($reply_id,$groups_json['groups'][$chat_id]['features'])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : $reply_tag

*تم رفعه مميز بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
$groups_json['groups'][$chat_id]['features'][] = $reply_id;
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*تم رفع هذا العضو مميز مسبقاً 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص الادمنية فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(preg_match("/^رفع مميز (.*)/",$text,$matches)){
if(is_admin($from_id,$chat_id) || in_array($reply_id,$groups_json['groups'][$chat_id]['managers'])){
if(!in_array(id($matches[1]),$groups_json['groups'][$chat_id]['features'])){
$geinfo = get_info(id($matches[1]),"member")['title'];
$tg = "tg://user?id=".id($matches[1]);
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : [$geinfo]($tg)

*تم رفعه مميز بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
$groups_json['groups'][$chat_id]['features'][] = id($matches[1]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*تم رفع هذا العضو مميز مسبقاً 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص الادمنية فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "تنزيل مميز" && isset($reply_id)){
if(is_admin($from_id,$chat_id) || in_array($reply_id,$groups_json['groups'][$chat_id]['managers'])){
if(in_array($reply_id,$groups_json['groups'][$chat_id]['features'])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : $reply_tag

*تم تنزيله من المميز بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
unset($groups_json['groups'][$chat_id]['features'][array_search($reply_id,$groups_json['groups'][$chat_id]['features'])]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا العضو ليس مميز مسبقاً 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص الادمنية فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "الاوامر"){
if(is_admin($from_id,$chat_id) || in_array($reply_id,$groups_json['groups'][$chat_id]['managers'])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"
اهلا بك : $tag
 
*في قائمة الاوامر الاساسية ✅*
•--------------» $channel «--------------•
`م1` •⊱ *لعرض اوامر البحث*
`م2` •⊱ *لعرض اوامر القفل والفتح*
`م3` •⊱ *لعرض اوامر الرفع والتنزيل*
`م4` •⊱ *لعرض اوامر الحماية*
•--------------» $channel «--------------•",
'reply_to_message_id'=>$message_id,
'disable_web_page_preview'=>true,
]);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص الادمنية فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "الاعدادات"){
if(is_admin($from_id,$chat_id) || in_array($reply_id,$groups_json['groups'][$chat_id]['managers'])){
$arr = array_combine($en,$ar);
$ok['yes'] = '✔';
$ok['no'] = '✖';
foreach ($groups_json['groups'][$chat_id]['setting'] as $k => $v){
$res .= "*".$arr[$k]."* : ".$ok[$v]."\n";
}
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"
اهلا بك : $tag

 ✔ :: تعني مسموح 
✖ :: تعني غير مسموح 

•--------------» $channel «--------------•
$res •--------------» $channel «--------------•",
'reply_to_message_id'=>$message_id,
'disable_web_page_preview'=>true,
]);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص الادمنية فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "م1"){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"
اهلا بك : $tag
 
*في قائمة اوامر البحث ✅*

🌸 ¦ `سورة: اسم السورة` ~ *لارسال السورة على شكل ملف mp3*

🌸 ¦ `اية: ما تذكره من اية` ~ *للبحث عن آية*

🌸 ¦ `صفحة: رقم الصفحة` ~ *لإرسال صورة الصفحة في القرآن الكريم مع ملف mp3*

🌸 ¦ `حديث: ما تذكره من الحديث` ~ *للبحث عن الحديث*

🌸 ¦ `صحيح 'اسم الصحيح' | حديث: رقم الحديث` ~ *لإرسال الحديث*

🌸 ¦ `كتاب: اسم كتاب` ~ *للبحث عن كتاب*

🌸 ¦ `مقاطع قصيرة` ~ *لإرسال مقطع قصير*",
'reply_to_message_id'=>$message_id,
'disable_web_page_preview'=>true,
]);
return 0;
}
if($text == "م2"){
if(is_admin($from_id,$chat_id) || in_array($reply_id,$groups_json['groups'][$chat_id]['managers'])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"
اهلا بك : $tag
 
*في قائمة القفل والفتح ✅*
•--------------» $channel «--------------•
💥¦ قفل ~ فتح •⊱ *الكل* ♻
💥¦ قفل ~ فتح •⊱ *الدردشة* ♻
💥¦ قفل ~ فتح •⊱ *الصوتيات* ♻
💥¦ قفل ~ فتح •⊱ *الفيديوهات* ♻
💥¦ قفل ~ فتح •⊱ *الصور* ♻
💥¦ قفل ~ فتح •⊱ *الملصقات* ♻
💥¦ قفل ~ فتح •⊱ *المتحركات* ♻
💥¦ قفل ~ فتح •⊱ *الملفات* ♻
💥¦ قفل ~ فتح •⊱ *الروابط* ♻
💥¦ قفل ~ فتح •⊱ *القنوات* ♻
💥¦ قفل ~ فتح •⊱ *الماركداون* ♻
💥¦ قفل ~ فتح •⊱ *المعرفات* ♻
💥¦ قفل ~ فتح •⊱ *التعديل* ♻
💥¦ قفل ~ فتح •⊱ *الانلاين* ♻
💥¦ قفل ~ فتح •⊱ *التوجيه* ♻
💥¦ قفل ~ فتح •⊱ *الرسائل المزعجة* ♻
💥¦ قفل ~ فتح •⊱ *الاشعارات* ♻
•--------------» $channel «--------------•",
'reply_to_message_id'=>$message_id,
'disable_web_page_preview'=>true,
]);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص الادمنية فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "م3"){
if(is_admin($from_id,$chat_id) || in_array($reply_id,$groups_json['groups'][$chat_id]['managers'])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"
اهلا بك : $tag
 
*في قائمة القفل والفتح ✅*
•--------------» $channel «--------------•
💥¦ رفع ~ تنزيل •⊱ *مدير* ♻
💥¦ رفع ~ تنزيل •⊱ *ادمن* ♻
💥¦ رفع ~ تنزيل •⊱ *مميز* ♻
•--------------» $channel «--------------•",
'reply_to_message_id'=>$message_id,
'disable_web_page_preview'=>true,
]);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص الادمنية فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "م4"){
if(is_admin($from_id,$chat_id) || in_array($reply_id,$groups_json['groups'][$chat_id]['managers'])){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"
اهلا بك : $tag
 
*في قائمة الحماية ✅*
•--------------» $channel «--------------•
💥¦ `تقييد` ~ `فك تقييد` ♻
💥¦ `كتم` ~ `الغاء الكتم` ♻
💥¦ `حظر` ~ `الغاء الحظر` ♻
💥¦ `طرد` ~ *لا يوجد الغاء طرد* 
•--------------» $channel «--------------•
💥¦ `المقيدين` •⊱ *لعرض المقيدين ♻*
💥¦ `المكتومين` •⊱ *لعرض المكتومين ♻*
💥¦ `المطرودين` •⊱ *لعرض المطردوين ♻*
💥¦ `المحظورين` •⊱ *لعرض المطردوين ♻*
•--------------» $channel «--------------•
💥¦ `مسح المقيدين` •⊱ *لمسح المقيدين ♻*
💥¦ `مسح المطرودين` •⊱ *لمسح المطرودين ♻*
💥¦ `مسح المكتومين` •⊱ *لمسح المطردوين ♻*
💥¦ `مسح المحظورين` •⊱ *لمسح المطردوين ♻*

•--------------» $channel «--------------•
💥¦ `تاك` ~ *لعمل تاك للاعضاء المتفاعلين ♻*
💥¦ `مسح الميديا` ~ *لمسح الميديا في الكروب ♻*
•--------------» $channel «--------------•
*ملاحظة ::* __يمكنك التقييد لمدة معينة وذلك بإضافة المدة بعد كلمة تقييد__ ،  مثال 👇🏼
`تقييد 5د`
•--------------» $channel «--------------•",
'reply_to_message_id'=>$message_id,
'disable_web_page_preview'=>true,
]);
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص الادمنية فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if(preg_match("/^تنزيل مميز (.*)/",$text,$matches)){
if(is_admin($from_id,$chat_id) || in_array($reply_id,$groups_json['groups'][$chat_id]['managers'])){
if(in_array(id($matches[1]),$groups_json['groups'][$chat_id]['features'])){
$geinfo = get_info(id($matches[1]),"member")['title'];
$tg = "tg://user?id=".id($matches[1]);
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"المستخدم : [$geinfo]($tg)

*تم تنزيله من المميز بنجاح ✅*

بواسطة : $tag",
'reply_to_message_id'=>$message_id,
]);
unset($groups_json['groups'][$chat_id]['features'][array_search(id($matches[1]),$groups_json['groups'][$chat_id]['features'])]);
file_put_contents('groups_json.json',json_encode($groups_json,64|128|256));
return 0;
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا العضو ليس مميز مسبقاً 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"*هذا الأمر يخص الادمنية فقط 🔴*",
'reply_to_message_id'=>$message_id,
]);
return 0;
}}
if($text == "ايدي"){
if(is_admin($from_id,$chat_id,"view") == "creator"){
$r .= " مالك";
}
if(is_admin($from_id,$chat_id,"view") == "administrator"){
$r .= " ادمن";
}
if(in_array($from_id,$groups_json['groups'][$chat_id]['managers'])){
$r .= " مدير";
}
if(in_array($from_id,$groups_json['groups'][$chat_id]['features'])){
$r .= " مميز";
}
$r = empty ($r) ? "عضو" : str_replace(" "," , ",$r);
$token = API_KEY;
$send = json_decode(file_get_contents("https://api.telegram.org/bot$token/GetUserProfilePhotos?user_id=".$from_id),true);
$s = bot('sendphoto',[
'chat_id'=>$chat_id,
'photo'=>$send['result']['photos'][0][0]['file_id'],
'parse_mode'=>"MarkDown",
'caption'=>"
✅¦ اسمك •⊱ *$name*
✅¦ ايديك •⊱ `$from_id`
✅¦ رتبتك •⊱ *$r*",
'reply_to_message_id'=>$message_id,
]);
if($s->ok !== true){
bot('sendmessage',[
'chat_id'=>$chat_id,
'parse_mode'=>"MarkDown",
'text'=>"
✅¦ اسمك •⊱ *$name*
✅¦ ايديك •⊱ `$from_id`
✅¦ رتبتك •⊱ *$r*",
'reply_to_message_id'=>$message_id,
]);
}}
if($message && !$text){
$groups_json["groups"][$chat_id]["media"][] = $message_id;
file_put_contents("groups_json.json",json_encode($groups_json,64|128|256));
}
if($message && !in_array($from_id,$groups_json["groups"][$chat_id]["ids"])){
$groups_json["groups"][$chat_id]["ids"][] = $from_id;
file_put_contents("groups_json.json",json_encode($groups_json,64|128|256));
}
if($message){
$groups_json["spam"][$d][$chat_id][$from_id] += 1;
file_put_contents("groups_json.json",json_encode($groups_json,64|128|256));
}
}
$admin = $sudo[0];
while(true){
try{
	@$update_id = $update_id ?? 0;
	@$update = getupdate($update_id+1);
	@$ok = run($update);
	@$update_id = $update->update_id;
	if($ok == 'stop'){
		@$update = getupdate($update_id+2);
	  	break;
	  }
} catch (Exception $e) {
bot('sendmessage',[
'chat_id'=>$sudo[0],
'text'=>$e->getMessage(),
]);
break;
}
}