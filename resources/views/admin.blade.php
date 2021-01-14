<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Blog1997 | admin</title>
    <link rel="stylesheet" href="{{mix('vue/css/app.css')}}">
    <link rel="stylesheet" href="{{mix('vue/vendor.css')}}">
</head>
<body>
    <div id="app"></div>
</body>
<script src="{{mix('vue/manifest.js')}}"></script>
<script src="{{mix('vue/vendor.js')}}"></script>
<script type="text/javascript" src="{{mix('vue/main.js')}}"></script>
</html>