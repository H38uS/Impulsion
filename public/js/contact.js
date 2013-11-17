$(document).ready(function() {	
	$(".sendemail").click(function() {
		var form = $("form");
		form.show("slow");
		$('select option[@value='+this.id+']').attr("selected", "selected");
	});	
});

function checkform() {
    var mail = $("#mailaddress").val();
    var firstname = $("#firstname").val();
    var surname = $("#surname").val();
    var subject = $("#subject").val();
    var message = $("#message").val();
    var valid = true;
	
    $("#mailerror").text("");
    $("#firstnameerror").text("");
    $("#surnameerror").text("");
    $("#subjecterror").text("");
    $("#messageareaerror").text("");
	
    if (mail == "") {
        valid = false;	
        $("#mailerror").text("Veuillez entrer une adresse mail.");
    } else {
        var reg=/^([a-zA-Z0-9\-\._]+)@(([a-zA-Z0-9\-_]+\.)+)([a-z]{2,3})$/;
        if (!reg.test(mail)) {
            $("#mailerror").text("Veuillez entrer une adresse mail valide.");
            valid = false;
        }
    }
	
    if (firstname == "") {
        valid = false;	
        $("#firstnameerror").text("Veuillez entrer votre prénom.");
    }
	
    if (surname == "") {
        valid = false;
        $("#surnameerror").text("Veuillez entrer votre nom.");
    }
	
    if (subject == "") {
        valid = false;	
        $("#subjecterror").text("Veuillez entrer un objet.");
    }
	
    if (message == "") {
        valid = false;	
        $("#messageareaerror").text("Veuillez entrer un message.");
    }
	
    if (valid)
        loading("Envoie du mail en cours...");
	
    return valid;	
}
