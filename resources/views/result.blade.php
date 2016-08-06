<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
    </head>
    <body>
you can search
{!! Form::open(array('url' => '/search', 'method' => 'post')) !!}
user_id: {!! Form::text('user_id'); !!}
{!! Form::submit('Click Me!') !!}
{!! Form::close() !!}

result
</html>
