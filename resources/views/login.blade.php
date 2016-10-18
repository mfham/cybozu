<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
    </head>
    <body>
{!! Form::open(array('url' => '/home', 'method' => 'post')) !!}
id: {!! Form::text('username'); !!}
pass: {!! Form::password('password'); !!}
{!! Form::submit('Click Me!') !!}
{!! Form::close() !!}
    </body>
</html>
