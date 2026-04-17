<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attestation</title>
</head>
    <body>

        <h2 style="text-align:center;">ATTESTATION</h2>

        <p><strong>Référence :</strong> {{ $declaration->reference }}</p>
        <p><strong>Entreprise :</strong> {{ $declaration->entreprise->nom }}</p>
        <p><strong>Date :</strong> {{ now()->format('d/m/Y') }}</p>

        <hr>

        <p>
            Cette attestation certifie que la déclaration a été traitée et validée.
        </p>

    </body>
</html>