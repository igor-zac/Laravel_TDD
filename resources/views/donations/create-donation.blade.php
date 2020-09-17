<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="antialiased">
<div>
    <h1>Donation</h1>

    <h2>{{ $project->name }}</h2>
    <p>Description: <br> {{$project->description}} <br/></p>
    <p>Author: {{$project->user->name}}</p>
    <p>Date: {{$project->updated_at}}</p>

    <form action="{{route('donations.store',["project" => $project->id])}}" method="post">

        <label for="donation_amount">Amount :</label><br>
        <input type="number" id="donation_amount" name="amount"><br>

        <button type="submit">Donate</button>

    </form>

</div>
</body>
</html>

