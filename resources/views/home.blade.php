<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
    </head>
    <body>
{!! Form::open(array('url' => '/search', 'method' => 'post')) !!}
<p>
date:</br>
@include('date')
</p>
<p>
the number of displayed day:</br>
@include('displayedDay')
</p>
<p>
the number per day:</br>
@include('perDay')
</p>
<p>
minutes:</br>
@include('minute')
</p>
<p>
users:</br>
@include('user')
</p>
<p>
facilities:</br>
@include('facility')
</p>
{!! Form::submit('Click Me!') !!}
{!! Form::close() !!}
    </body>
</html>
