<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Arturs Lukjanenoks">

    <title>LenderHomePage Test</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>

<div id="app">
    <router-view></router-view>

    <notifications position="top right"/>
</div>

<script>
    window.host = '{{ url('/') }}';
</script>

<script src="{{ asset('js/app.js') }}"></script>

</body>

</html>
