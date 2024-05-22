<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle commande de {{$session->customer_details->name}}</title>
</head>
<body>
    <h1>Merci pour votre commande !</h1>
    <p>Le montant du panier est de {{ $session->amount_total / 100}} {{ $session->currency }}.</p>
    {{-- <p>Autre demande: {{$session->custom_fields->text->value}}</p> --}}
    <p></p>
    <div>
        <p>L'adresse de livraison :</p>
        <div>
            <p>Ville : {{$session->customer_details->address->city}}</p>
            <p>Code postal : {{$session->customer_details->address->postal_code}}</p>
            <p>Adresse : {{$session->customer_details->address->line1}}</p>
        </div>
    </div>
    <p>Email : {{$session->customer_details->email}}</p>

</body>
</html>
