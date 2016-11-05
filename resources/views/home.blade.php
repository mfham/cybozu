<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
    </head>
    <body>
{!! Form::open(array('url' => '/search', 'method' => 'post')) !!}
week: {!! Form::text('weeks'); !!}
minutes: {!! Form::text('minutes'); !!}
user_ids: {!! Form::text('user_ids'); !!}
<p>
{{Form::checkbox('facility[]', 4, false)}}第一会議室
{{Form::checkbox('facility[]', 5, false)}}第二会議室
{{Form::checkbox('facility[]', 6, false)}}セミナールーム
</p>
{!! Form::submit('Click Me!') !!}
{!! Form::close() !!}
    </body>
</html>
