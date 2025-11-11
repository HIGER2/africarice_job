<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Candidature envoyée avec succès | Application successfully submitted</title>
</head>
<body>
    <h4>✅ Application successfully submitted</h4>
    <p>
        This is an automated reply that confirms your online application was completed for the role of <strong>{{ $candidature->publication->job->position_title }}</strong>.
        Please note that only short-listed candidates will be contacted.
        If you have any questions, please contact us via email at 
    </p>

    <p>Kind regards.</p>
    <p>AfricaRice HR</p>
    
    <h4>✅ Candidature envoyée avec succès</h4>

    <p>
        Candidature envoyée avec succès
        Ceci est une réponse automatique confirmant que votre candidature en ligne a été validée pour le poste de   <strong>{{ $candidature->publication->job->position_title }}</strong>.
        Veuillez noter que seul(e)s les candidat(e)s présélectionné(e)s seront contacté(e)s. Pour toute autre question, veuillez nous contacter par courriel à l'adresse <a href="mailto:africaricehr@cgiar.org">africaricehr@cgiar.org.</a>
    </p>

    <p>Cordialement</p>
    <p>AfricaRice HR</p>
</body>
</html>
