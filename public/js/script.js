$("#envoi").click(function(e){
    e.preventDefault(); // on empêche le bouton d'envoyer le formulaire
    var message = encodeURIComponent( $('#message').val() );
    var author_id=$('#author p').attr('id');
    var receiver_id=$('#receiver p').attr('id');
    if(message != ""){ // on vérifie que les variables ne sont pas vides
        $.ajax({
            url : "traitement", // on donne l'URL du fichier de traitement
            type : "POST", // la requête est de type POST
            data : "message=" + message+ "&author_id="+ author_id +"&receiver_id="+ receiver_id +"&submit" // et on envoie nos données
        });
        document.getElementById('message').value="";
    }
});

function charger(){
    setTimeout( function(){
        var premierID = $('#messages scroll-page:first').attr('id'); // on récupère l'id le plus récent
        var author_id=$('#author p').attr('id');
        var receiver_id=$('#receiver p').attr('id');
        if(typeof premierID === 'undefined')premierID=1;
        $.ajax({
            url : "charger", // on passe l'id le plus récent au fichier de chargement
            type : "POST",
            data : "premierID="+ premierID+"&author_id="+ author_id +"&receiver_id="+ receiver_id ,
            success : function(html){
                $('#messages').prepend(html);  }
        });
        charger();
    }, 1000);
}


charger();
