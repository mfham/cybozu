<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
    </head>
    <body>
{!! Form::open() !!}
id: {!! Form::text('username'); !!}
pass: {!! Form::text('password'); !!}
{!! Form::close() !!}
    </body>
</html>
