
function validateFormName(x,z,w,v) {
			var minLength = 8; //Minimum characters
			var maxLength = 15; //Maximum characters
            var helpText = document.getElementById(z); //ID error message
            //alert('hola');
            var x = x.value; //input value name
            var y = /^[A-Za-záíóúñÑé0-9]+$/; //constrain of characters
            if ( !x.match(y) || x.length < minLength || x.length > maxLength || x === null || x == '' ) {
                if(helpText != null){
                    helpText.style.color = 'red'; //Prompt message character color
                    helpText.innerHTML = 'Username not valid.';
                    document.getElementById(w).className = "form-group   has-error   has-feedback"; 
                    document.getElementById(v).className = "glyphicon glyphicon-remove form-control-feedback"; 
                }
                return false;
            }
            else{
                if(helpText != null){
                    helpText.innerHTML = '';
                    document.getElementById(v).className = "glyphicon   glyphicon-ok     form-control-feedback";
                    document.getElementById(w).className = "form-group has-success has-feedback"; 
                }
                return true;
            }
}

 function validateFormPwd(x,w,v) {
 			var minLength = 8;
			var maxLength = 15;
            var helpText = document.getElementById('pwd_help');
            var x = x.value;
            if( x.length < minLength || x.length > maxLength || x.length === 0 || x === null ){
                 
                if(helpText != null){
                    helpText.style.color = "red";
                    helpText.innerHTML = "Password not valid.";
                    document.getElementById(w).className = "form-group   has-error   has-feedback"; 
                    document.getElementById(v).className = "glyphicon glyphicon-remove form-control-feedback";
                }
                return false;
            }
            else{
                if(helpText != null){
                    document.getElementById(v).className = "glyphicon glyphicon-ok     form-control-feedback";
                    document.getElementById(w).className = "form-group has-success has-feedback"; 
                    helpText.innerHTML = '';
                }
                return true;
            }

}
 function validateCaseNumber(x) {
            var helpText = document.getElementById('caseNumber_help');
            var x = x.value;
                if(helpText != null){
                    helpText.style.color = "red";
                    helpText.innerHTML = "Case Number not valid.";
                }
                return false;
            }

 function validateConctractNumber(x) {
            var helpText = document.getElementById('contractNumber_help');
            var x = x.value;
                if(helpText != null){
                    helpText.style.color = "red";
                    helpText.innerHTML = "Case Number not valid.";
                }
                return false;
            }


 function validateEmail(x) {
            var helpText = document.getElementById('email_help');
            var x = x.value;
                if(helpText != null){
                    helpText.style.color = "red";
                    helpText.innerHTML = "Email already exists.";
                }
                return false;
            }
   

function validateUsername(x) {
            var helpText = document.getElementById('username_help');
            var x = x.value;
                if(helpText != null){
                    helpText.style.color = "red";
                    helpText.innerHTML = "Incorrect Username.";
                }
                return false;
            }

function validateNewUsername(x) {
            var helpText = document.getElementById('newUsername_help');
            var x = x.value;
                if(helpText != null){
                    helpText.style.color = "red";
                    helpText.innerHTML = "Username already exists.";
                }
                return false;
            }

function validatePassword(x) {
            var helpText = document.getElementById('password_help');
            var x = x.value;
                if(helpText != null){
                    helpText.style.color = "red";
                    helpText.innerHTML = "Incorrect password.";
                }
                return false;
            }

function validateNewPassword(x) {
            var helpText = document.getElementById('newPassword_help');
            var x = x.value;
                if(helpText != null){
                    helpText.style.color = "red";
                    helpText.innerHTML = "Password already exists.";
                }
                return false;
            }
