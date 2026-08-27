var now_chatting_with_id = 0; //globaly store the user id of the person we are chatting with
var msg_id = 0;
var final_chat_id = 0;
var notification_saved = "";
var notifation_firt = true;

function $$(element){
    return document.getElementById(element);
}   
function collect_data(find,request_type,data = {}){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            result_data(this.responseText,request_type);
            //alert(this.responseText);
        }
    }
    data.find = find;
    data.request_type = request_type;
    data = JSON.stringify(data);
    xhr.open("POST","api2.php",true);
    xhr.send(data);
}

function result_data(result,request_type){
    //console.log(result);
    if(result){
        var obj = JSON.parse(result);
        switch(request_type){
            case "user_info":
                if(obj.logged_in == false){
                    window.location.href = "api.php";
                    return;
                }
                $$("login_name").innerHTML = obj.name; 
                $$("user_id").innerHTML += obj.user_id; 
                if(obj.profile){ // to insert image into settings user profile
                    let user_profile_src = obj.profile;
                    $$("profile_pic_tag").src = "includes/uploads/"+user_profile_src;
                    $$("edit_profile_img").src = "includes/uploads/"+user_profile_src;
                }else{
                    if(obj.gender == "male"){
                        $$("profile_pic_tag").src = "src_img/male676.png";
                        $$("edit_profile_img").src = "src_img/male676.png";
                    }else if(obj.gender == "female"){
                        $$("profile_pic_tag").src = "src_img/female1673.png";
                        $$("edit_profile_img").src = "src_img/female1673.png";
                    }else{
                        $$("profile_pic_tag").src = "src_img/not-to-say.png";
                        $$("edit_profile_img").src = "src_img/not-to-say.png";
                    }
                }
                //settings info loading;
                document.getElementsByName("name")[0].value = obj.name;
                document.getElementsByName("username")[0].value = obj.username;
                document.getElementsByName("email")[0].value = obj.e_mail;
                document.getElementsByName("phone")[0].value = obj.phone;
                document.getElementsByName("password")[0].value = obj.pass_word;
                document.getElementsByName("password_confirm")[0].value = obj.pass_word;
                if(obj.gender == "male"){
                    document.getElementsByName("gender")[0].checked = true;
                }else if(obj.gender == "female"){
                    document.getElementsByName("gender")[1].checked = true;
                }else{
                    document.getElementsByName("gender")[2].checked = true;
                }

                break;
            case "logout":
                if(obj == "logged out"){
                    window.location.href = "api.php";
                }
                //console.log(result);
                break;
            case "contact":
                //console.log(result);
                var recent_contacts = $$("recent_contacts");
                recent_contacts.innerHTML = "";
                var element = document.createElement("div");
                element.className = "contact_list_dynamic";
                element.id = "contact_list_dynamic_id"
                element.innerHTML = obj.list_code;
                recent_contacts.appendChild(element); 
                break; 
            case "settings":
                //console.log(result);
                settings_error_display(result);
                break;
            case "search_contact":
                var contacts_searched = $$("contacts_searched");
                contacts_searched.innerHTML = "";
                var element = document.createElement("div");
                element.className = "contact_list_dynamic";
                element.innerHTML = obj.list_code;
                contacts_searched.appendChild(element); 
                // checking input length
                break;
            case "get_messages":
                //console.log(result);
                if(obj.type == "all"){
                    final_chat_id = obj.final_chat_id;
                    display_msg_in_chat(obj.chat);
                }else{

                } 
                break;
            case "delete_message":
                console.log(obj.delete);
                if(obj.deleted){
                    read_messages("all");
                }
                break;
            case "notification":
                //console.log(result);
                if(notifation_firt){
                    notifation_firt = false;
                    notification_saved  = result;
                    console.log("first time");
                    display_notification(obj);
                }else {
                    if(notification_saved  != result){
                        notification_saved = result;
                        console.log("Not first time");
                        display_notification(obj)
                    }
                }
                break;
        }
    }
}
collect_data("null","user_info");
function $_logout(){
    collect_data("null","logout");
   // alert("logout");
}
collect_data("null","contact"); // load info of peoples you might know



//conform logout----------------------------------------------------------------------------------------------------
function confrm_atrt(){
    var txt = "<div id='alrt-frame'><label class='alrt-msg'>Are you sure to logout<span> ?</span></label> <br> <input type='button' value='cancel' class='confrm-btn _1' onclick='back_to_app()'> &nbsp; <input type='button' value='Logout' class='confrm-btn _2' onclick='$_logout()'> </div>";
    var element = document.createElement("div");
    element.id = "alrt_box"
    element.innerHTML = txt;
    var alrt_section = document.getElementById("alrt_section");
    alrt_section.appendChild(element);
    // to avoid over clicking
    document.getElementById("logout-btn").disabled = true;
}function back_to_app(){
    var alrt_box = document.getElementById("alrt_box");
    alrt_box.remove();
    document.getElementById("logout-btn").disabled = false;
}
//----------------------------------------------------------------------------------------------------------------------------------


// profile showing------------------------------------------------------------------------------------------------------------------
let is_cicked_profile = true;
function show_profile_recent(image_src){
    if(is_cicked_profile){
        var txt = "<div id='show-profile-recent'> <button id='show_profile_button' onclick='close_popup()'><sup>x</sup></button> <img id='show-profile_image_popup' src='"+image_src+"'> </div>";
        var element = document.createElement("div");
        element.id = "profile_clip";
        element.innerHTML = txt;
        var show_profile_div = document.getElementById("show_profile_div");
        show_profile_div.appendChild(element);
        is_cicked_profile = false;
    }
}function close_popup(){ // close the opened profile popup
    var profile_clip = document.getElementById("profile_clip");
    profile_clip.remove();
    is_cicked_profile = true;
} //----------------------------------------------------------------------------------------------------------------------------------

// update profile settings------------------------------------------------------------------------------------------------------------
let save_settings = $$("save_settings");
save_settings.addEventListener('click',collect_settings_data);
function collect_settings_data(){
    save_settings.disabled = true;
    save_settings.value = "Loading...";
    let selector = $$("selector");
    let inputs = selector.getElementsByTagName("INPUT");
    let data = new Object();
    let iLength=inputs.length - 1;
    for (let i=0; i <= iLength; i++) {
        var key = inputs[i].name ;
        switch(key){
            case "name":
                data.name = inputs[i].value;
                break;
            case "username":
                data.username = inputs[i].value;
                break;
            case "email":
                data.email = inputs[i].value;
                break;
            case "phone":
                data.phone = parseInt(inputs[i].value);
                break;   
            case "password":
                data.password = inputs[i].value;
                break;         
            case "password_confirm":
                data.password_confirm = inputs[i].value;
                break;   
        }
    }
    if(document.getElementsByName("gender")[0].checked){
        data.gender = document.getElementsByName("gender")[0].value;
    }else if(document.getElementsByName("gender")[1].checked){
        data.gender = document.getElementsByName("gender")[1].value;
    }else{
        data.gender = document.getElementsByName("gender")[2].value;
    }
    console.log(data);
    collect_data("null","settings",data);
}function settings_error_display(result){
    let settings_data_message = JSON.parse(result);
    // console.log(settings_data_message);
    var user_fault = false;
    var err_fullname = $$("err_fullname");
    var err_username = $$("err_username");
    var err_email = $$("err_email");
    var err_phone_number = $$("err_phone_number");
    var err_password = $$("err_password");
    var err_password_confirm = $$("err_password_confirm");
    //error full name
    if(settings_data_message.name == ""){
        err_fullname.innerHTML = "";
    }else{
        err_fullname.innerHTML = settings_data_message.name;
        user_fault = true;
    }
    //error user name
    if(settings_data_message.username == ""){
        err_username.innerHTML = "";
    }else{
        err_username.innerHTML = settings_data_message.username;
        user_fault = true;
    }
    // error email
    if(settings_data_message.email == ""){
        err_email.innerHTML = "";
    }else{
        err_email.innerHTML = settings_data_message.email;
        user_fault = true;
    }
    //error phone number
    if(settings_data_message.phone_number == ""){
        err_phone_number.innerHTML = "";
    }else{
        err_phone_number.innerHTML = settings_data_message.phone;
        user_fault = true;
    }
    //error password
    if(settings_data_message.password == ""){
        err_password.innerHTML = "";
    }else{
        err_password.innerHTML = settings_data_message.password;
        user_fault = true;
    }
    //error conform password
    if(settings_data_message.password_confirm == ""){
        err_password_confirm.innerHTML = "";
    }else{
        err_password_confirm.innerHTML = settings_data_message.password_confirm;
        user_fault = true;
    }

    if(settings_data_message.info_type == true){
        async function _save_settings_btn_disabler_() {
            save_settings.value = "Settings Updated";
            save_settings.disabled = true; 6
            await sleep(5000); // Pause for 5 seconds
            save_settings.value = "Save Settings";
            save_settings.disabled = false;
            //reloading the basic user info
            collect_data({},"user_info");
            collect_data({},"contact");
        }
        _save_settings_btn_disabler_(); 
    }else if(!user_fault){
        alert("something went wrong");
    }  
} //------------------------------------------------------------------------------------------------------------------------------------


//upload image action-------------------------------------------------------------------------------------------------------------------
$$("upload_profile_image").addEventListener("click", function(){
    $$("real_image_input").click();
});
function reload_apllication(){   // function for reloading the basic user info
    collect_data({},"user_info");
    collect_data({},"contact");
}

function _upload_image_(files){
    var data_form = new FormData();
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            console.log(this.responseText);
             _upload_image_result(this.responseText);
        }
    }
    data_form.append('file',files[0]);
    xhttp.open("POST","includes/upload_profile_image.php",true);
    xhttp.send(data_form);

}
//button disabler
function _upload_image_result(result){
    let upload_img_data = JSON.parse(result);
    if(upload_img_data.info_type == true){
        var upload_profile_image = $$("upload_profile_image");
        var upload_profile_image_text = $$("upload_profile_image_text");  
        async function upload_profile_image_btn_disabler() {
            upload_profile_image_text.innerText = "....";
            upload_profile_image.disabled = true;
            reload_apllication(); //reloading the basic user info
            await sleep(3000); // Pause for 3 seconds
            upload_profile_image_text.innerText = "Edit";
            upload_profile_image.disabled = false;
        }
        upload_profile_image_btn_disabler(); 
    }else{
        alert("sorry...something went wrong");
    }
} //----------------------------------------------------------------------------------------------------------------------------------

// contact search---------------------------------------------------------------------------------------------------------------------
var search_bar = $$("search_bar");
search_bar.value = "";
search_bar.addEventListener('input',function(){
    var search_bar_input_txt = search_bar.value.trim();
    search_bar_input_txt = $$("search_bar").value.trim();
    if(search_bar_input_txt.length > 2){
        var search_contact = new Object();
        search_contact.search_name = search_bar_input_txt;
        collect_data("null","search_contact",search_contact);
    }
    // console.log(search_bar_input_txt);
});
function show_profile_search(image_src){
    if(is_cicked_profile){
        var txt = "<div id='show-profile-search'> <button id='show_profile_button' onclick='close_popup()'><sup>x</sup></button> <img id='show-profile_image_popup' src='"+image_src+"'> </div>";
        var element = document.createElement("div");
        element.id = "profile_clip";
        element.innerHTML = txt;
        var show_profile_div = document.getElementById("show_profile_div");
        show_profile_div.appendChild(element);
        is_cicked_profile = false;
    }
}

//------------------------------------------------------------------------------------------------------------------------------------


// function load contact chat-----------------------------------------------------------------------------------------------------------------
function load_contact_chat(user_chat_id,type,pic_url,name_character_replaced_txt,date_character_replaced_txt){
    function replace_char(originalString, targetChar, replacementChar) {
        return originalString.replaceAll(targetChar, replacementChar);
    }
    var name = replace_char(name_character_replaced_txt, "@", " ");
    var date = replace_char(date_character_replaced_txt,"@","<br>")
    $$("chat_bar_header_img").src = pic_url;
    $$("chat_bar_header_name").innerHTML = name;
    $$("chat_bar_last_visited_date").innerHTML = "Last visited on : "+date; 

    if(type == "search_contact"){
        $$("chat-recent_bar").style.display = "flex";
        $$("main_contact_div").style.display = "none";
        $$("user_settings").style.display = "none";
        if(window.innerWidth < 800){
            $$("chat_bar").style.width = "100%";
            $$("chat_bar").style.visibility = "visible";
            $$("recent_bar").style.width = "0px";
            $$("recent_bar").style.visibility = "hidden";
        }
    }else if (type == "recent_contact") {
        if(window.innerWidth < 800){
            $$("chat_bar").style.width = "100%";
            $$("chat_bar").style.visibility = "visible";
            $$("recent_bar").style.width = "0px";
            $$("recent_bar").style.visibility = "hidden";
        }
    }  
    now_chatting_with_id = user_chat_id;
    $$("active_chat_container").innerHTML = ""; // removing all previous chat when clicking next user 
    $$("contact_user_"+user_chat_id).style.backgroundColor = "#30312f"; // below three line of code remove notification form clicked user
    $$("user_notification_"+user_chat_id).innerHTML = "";
    $$("user_notification_"+user_chat_id).style.visibility = "hidden"; 
    read_messages("all");
} //---------------------------------------------------------------------------------------------------------------------------------



// message displaying option important session--------------------------------------------------------------------------------------
function read_messages(type) {
    let users_id_form = new Object();
    users_id_form.now_chatting_with = now_chatting_with_id;
    users_id_form.type = type;
    users_id_form.msg_id = final_chat_id;
    collect_data("null","get_messages",users_id_form);
    collect_data("null","notification");
} 
// for displaying active chat messages 
function display_msg_in_chat(chat_messages){           
    var active_chat_container = $$("active_chat_container");
    active_chat_container.innerHTML = chat_messages;
    active_chat_container.scrollTop = 10000000000000;
}
//for continuesily checking for messages and notifications
function check_for_new_message_and_notification (callback, ms) {
    setInterval(callback, ms);
}
check_for_new_message_and_notification (function () {
    read_messages("new");
}, 500);
//-------------------------------------------------------------------------------------------------------------------------------------------


//message sending options important section--------------------------------------------------------------------------------------------------
let message_sent_button = $$("message_sent_button");
let input_typing_box = $$("input_typing_box");
var user_message_typed;
message_sent_button.addEventListener('click',function (){
    user_message_typed = input_typing_box.value;
    if(user_message_typed.length > 0){
        input_typing_box.value = "";
        let message = new Object();
        message.message  = user_message_typed;
        message.reciver = now_chatting_with_id;
        collect_data("null","send_message",message);
        setTimeout(() => {read_messages("all");},500);
    }
});
//------------------------------------------------------------------------------------------------------------------------------------------

//delete chat messages
// creating dinamic html--------------------------------------------------------------------------------------------------------------------
let is_dlt_msg_ = true;
function option_btn_sent_(_msg_id_sent){
    if(is_dlt_msg_){
        var txt = "<div id='dlt_alert'> <button id='delete_from_all' onclick='messageDelete("+_msg_id_sent+",3)'>delete from all</buton> <button id='delete_for_me' onclick='messageDelete("+_msg_id_sent+",1)'>delete for me</button> <button id='close_dlt_alert_btn' onclick='close_dlt_alert()' style='background: #ff0000; margin-top: 10px;'>close</button> </div>";
        var element = document.createElement("div");
        element.id = "dlt_alert_frame_";
        element.innerHTML = txt;
        var alrt_section = document.getElementById("alrt_section");
        alrt_section.appendChild(element);
        is_dlt_msg_ = false;
    }
}function option_btn_recived_(_msg_id_recive){
    if(is_dlt_msg_){
        var txt = "<div id='dlt_alert'> <button id='delete_for_me' onclick='messageDelete("+_msg_id_recive+",2)'>delete for me</button> <button id='close_dlt_alert_btn' onclick='close_dlt_alert()' style='background: #ff0000; margin-top: 10px;'>close</button> </div>";
        var element = document.createElement("div");
        element.id = "dlt_alert_frame_";
        element.innerHTML = txt;
        var alrt_section = document.getElementById("alrt_section");
        alrt_section.appendChild(element);
        is_dlt_msg_ = false;
    }
}
function close_dlt_alert(){ // closing the open popup delete window
    var dlt_alert_frame_ = document.getElementById("dlt_alert_frame_");
    dlt_alert_frame_.remove();
    is_dlt_msg_ = true;
}

function messageDelete(msg_dlt_id,type){
    // type 1 means sender delete for me 
    // type 2 means reciver delete for me
    // type 3 means delete for all in sender option
    var msg_delete = new Object()
    msg_delete.messageId = msg_dlt_id;
    msg_delete.oparation_type = type; 
    //console.log(msg_dlt_id);
    collect_data("null","delete_message",msg_delete);
    setTimeout(() => { close_dlt_alert(); }, 300);
} 
//---------------------------------------------------------------------------------------------------------------------------------------
 
//display notifications-------------------------------------------------------------------------------------------------------------------
function display_notification(data){
    for(var [user_id, value] of Object.entries(data)){
        $$("contact_user_"+user_id).style.backgroundColor = "#80ff001a"; 
        $$("user_notification_"+user_id).innerHTML = value;
        $$("user_notification_"+user_id).style.visibility = "visible"; 
        //console.log("contact_user_"+user_id,value);
        var container = document.getElementById("contact_list_dynamic_id");
        var targetElement = document.getElementById("contact_user_"+user_id);
        container.prepend(targetElement);
    }
}