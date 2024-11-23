<!DOCTYPE html>
<html>

<head>
    <title>Phân chia nhóm</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <a href="{{ route('createGroups') }}" class="btn btn-info">Tao group</a>
    <a href="{{ route('adminDashboard') }}" class="btn btn-info">Admin</a>
    <a href="{{ route('userDashboard') }}" class="btn btn-info">User</a>
</body>

</html>
