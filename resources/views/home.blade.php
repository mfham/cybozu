<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
    </head>
    <body>
you can search
{!! Form::open(array('url' => '/search', 'method' => 'post')) !!}
week: {!! Form::text('weeks'); !!}
minutes: {!! Form::text('minutes'); !!}
user_ids: {!! Form::text('user_ids'); !!}
facility_ids: {!! Form::text('facility_ids'); !!}
{!! Form::submit('Click Me!') !!}
{!! Form::close() !!}
    </body>
</html>
