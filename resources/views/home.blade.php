<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
    </head>
    <body>
{!! Form::open(array('url' => '/search', 'method' => 'post')) !!}
<p>
weeks:
@include('week')
</p>
<p>
minutes:
@include('minute')
</p>
<p>
users:
@include('user')
</p>
<p>
facilities:
@include('facility')
</p>
{!! Form::submit('Click Me!') !!}
{!! Form::close() !!}
    </body>
</html>
