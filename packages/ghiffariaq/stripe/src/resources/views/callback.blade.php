<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stripe Callback</title>
</head>
<body>
    @if(Session::has('error'))
        Callback page triggered. However issue encountered : {{Session::get('error')}}
    @endif
    <p>Your Payment Status: {{\Str::upper(request()->get('status'))}}</p>

</body>
</html>
