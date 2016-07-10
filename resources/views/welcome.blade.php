<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
    </head>
    <body>
{!! Form::open(array('url' => '/result', 'method' => 'post')) !!}
id: {!! Form::text('username'); !!}
pass: {!! Form::text('password'); !!}
{!! Form::submit('Click Me!') !!}
{!! Form::close() !!}
    </body>
</html>
