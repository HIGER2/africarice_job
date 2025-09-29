<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nouvelle candidature</title>
</head>
<body>
    <h2>📌 Nouvelle candidature reçue</h2>

    <p>Bonjour,</p>

    <p>Une nouvelle candidature a été soumise :</p>

    <ul>
        <li><strong>Nom :</strong> {{ $candidature->user->name }} {{ $candidature->user->last_name }}</li>
        <li><strong>Email :</strong> {{ $candidature->user->email }}</li>
        <li><strong>Poste :</strong> {{ $candidature->publication->job->position_title }}</li>
        <li><strong>Date de soumission :</strong> {{ $candidature->created_at->format('d/m/Y H:i') }}</li>
    </ul>

    <p>Merci de traiter cette candidature rapidement.</p>

    <p>💼 L’équipe RH</p>
</body>
</html>
