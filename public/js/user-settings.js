document.addEventListener(
"DOMContentLoaded",
()=>{


const csrf =
document
.querySelector(
'meta[name="csrf-token"]'
)
.content;




/*
 Toggle User Form
*/


const toggle =
document.getElementById(
"toggleFormBtn"
);


const form =
document.getElementById(
"userFormCard"
);



if(toggle){


toggle.onclick=()=>{


if(
form.style.display==="none"
){


form.style.display="block";


toggle.innerHTML=
"Close Form";


}

else{


form.style.display="none";


toggle.innerHTML=
"Add New User";


}



}


}






/*
 Live Search
*/


const search =
document.getElementById(
"searchUser"
);



if(search){


search.addEventListener(
"keyup",
()=>{


let value =
search.value
.toLowerCase();



document
.querySelectorAll(
"tbody tr"
)
.forEach(row=>{


row.style.display =

row.innerText
.toLowerCase()
.includes(value)

?

""

:

"none";



});


});


}







/*
 Delete Confirmation
*/


document
.querySelectorAll(
".delete-btn"
)
.forEach(btn=>{


btn.onclick=(e)=>{


if(
!confirm(
"Delete this user?"
)
)

{


e.preventDefault();


}



};


});









/*
 Approve User AJAX
*/


document
.querySelectorAll(
".approve-user"
)
.forEach(btn=>{


btn.onclick=function(){


let id =
this.dataset.id;



this.classList.add(
"loading"
);



fetch(

"/admin/users/"+id+"/approve",

{


method:"POST",


headers:{


"X-CSRF-TOKEN":
csrf,


"Accept":
"application/json"


}


}

)

.then(
response=>{


if(response.ok)

return response.json();


}

)

.then(()=>{


showToast(
"User approved successfully"
);



this
.closest(
".pending-item"
)
.remove();



})

.catch(()=>{


showToast(
"Something went wrong"
);


})

.finally(()=>{


this.classList.remove(
"loading"
);


});



};


});









/*
 Login As Button
*/


document
.querySelectorAll(
".login-user"
)
.forEach(btn=>{


btn.onclick=()=>{


let id =
btn.dataset.id;



showToast(
"Login as feature requires backend route"
);



};


});









/*
 Auto hide alerts
*/


document
.querySelectorAll(
".alert"
)
.forEach(alert=>{


setTimeout(()=>{


alert.style.opacity="0";


setTimeout(()=>{


alert.remove();


},500);



},3000);



});








function showToast(message){


let toast =
document.getElementById(
"toast"
);



toast.innerHTML =
message;


toast.style.display =
"block";



setTimeout(()=>{


toast.style.display =
"none";


},2500);



}





});
