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
    <h1>Nouveau projet</h1>

    <form action="{{route('projects.store')}}" method="post">

        <label for="project_name">Project name :</label><br>
        <input type="text" id="project_name" name="name"><br>

        <label for="project_description">Description :</label><br>
        <input type="text" id="project_description" name="description">

        <button type="submit">Submit project</button>

    </form>

</div>
</body>
</html>

