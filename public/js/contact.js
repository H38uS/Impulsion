$(document).ready(function() {
	$(".sendemail").click(function() {
		var form = $("form");
		form.show("slow");
		$('select option[value='+this.id+']').attr("selected", "selected");
	});	
});

function checkform() {
	
	$("#mailaddress").removeClass("is-invalid");
	$("#firstname").removeClass("is-invalid");
	$("#surname").removeClass("is-invalid");
	$("#subject").removeClass("is-invalid");
	$("#message").removeClass("is-invalid");
	
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
        $("#mailerror").prev().addClass("is-invalid");
    } else {
        var reg=/^([a-zA-Z0-9\-\._]+)@(([a-zA-Z0-9\-_]+\.)+)([a-z]{2,3})$/;
        if (!reg.test(mail)) {
            $("#mailerror").text("Veuillez entrer une adresse mail valide.");
            $("#mailerror").prev().addClass("is-invalid");
            valid = false;
        }
    }
	
    if (firstname == "") {
        valid = false;	
        $("#firstnameerror").text("Veuillez entrer votre prénom.");
        $("#firstnameerror").prev().addClass("is-invalid");
    }
	
    if (surname == "") {
        valid = false;
        $("#surnameerror").text("Veuillez entrer votre nom.");
        $("#surnameerror").prev().addClass("is-invalid");
    }
	
    if (subject == "") {
        valid = false;	
        $("#subjecterror").text("Veuillez entrer un objet.");
        $("#subjecterror").prev().addClass("is-invalid");
    }
	
    if (message == "") {
        valid = false;	
        $("#messageareaerror").text("Veuillez entrer un message.");
        $("#messageareaerror").prev().addClass("is-invalid");
    }
	
    if (valid)
        loading("Envoie du mail en cours...");
	
    return valid;	
}
