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
    <h1>Edit Project Info</h1>

    <form action="{{route('projects.update', ['project' => $project->id])}}" method="post">
        @method('PUT')

        <label for="project_name">Project name :</label><br>
        <input type="text" id="project_name" name="name" value="{{$project->name}}"><br>

        <label for="project_description">Description :</label><br>
        <input type="text" id="project_description" name="description" value="{{$project->description}}">

        <button type="submit">Save modifications</button>

    </form>

</div>
</body>
</html>

