<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Votre candidature a été soumise</title>
</head>
<body>
    <h2>✅ Candidature soumise avec succès</h2>

    <p>Bonjour {{ $candidature->user->name }},</p>

    <p>Votre candidature pour le poste <strong>{{ $candidature->publication->job->position_title }}</strong> a bien été soumise.</p>

    <p>Notre équipe examinera votre candidature et vous contactera si votre profil correspond au poste.</p>

    <p>Merci de votre intérêt pour notre entreprise.</p>

    <p>💼 L’équipe RH</p>
</body>
</html>
