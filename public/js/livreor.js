function checkform() {
    var auteur = $("#auteur").val();
    var commentaire = $("#commentaire").val();
    var valid = true;
	
    $("#nameerror").text("");
    $("#commenterror").text("");
    
    if (auteur == "") {
        valid = false;	
        $("#nameerror").text("Veuillez entrer votre nom.");
    } 
	
    if (commentaire == "") {
        valid = false;	
        $("#commenterror").text("Veuillez entrer un message.");
    }
	
    if (valid)
        loading("Ecriture du message en cours...");
    
    return valid;
}
