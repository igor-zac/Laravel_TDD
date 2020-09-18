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
    <h1>Liste des donations</h1>

    @isset($donations)
        @foreach ($donations as $donation)
            <div style="display: block; width:70%;color:white;">
                <p>Amount: <br> {{$donation->amount}} <br/></p>
                <p>Project: {{$donation->project->name}}</p>
                <a href="{{route('donations.show', ['donation' => $donation->id])}}}}">
                    <button type="button">Details</button>
                </a>
            </div>
        @endforeach
    @endisset($donations)
</div>
</body>
</html>

