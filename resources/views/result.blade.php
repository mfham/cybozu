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
facility_ids: {!! Form::text('facility_ids'); !!}
{!! Form::submit('Click Me!') !!}
{!! Form::close() !!}
@foreach ($schedule as $v)
  <div>
start: {{ $v['start'] }} end  : {{ $v['end'] }}<br>
  </div>
@endforeach
</html>
