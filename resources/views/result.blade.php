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
    <a href=http://192.168.56.201/cgi-bin/mfham/grn.cgi/schedule/add?bdate=2016-08-07&uid=67>link</a>


    <form name="schedule/add" id="schedule/add" method="post" action="/cgi-bin/mfham/grn.cgi/schedule/command_add?">
    <input type='hidden' name='tab_name' value="schedule/add">
        <input type="hidden" name="tmp_key" value="1470430261">
        <input type="hidden" name="allow_file_attachment" value="1">
        
    <div class="button">
    <button type="submit">Send your message</button>
    </div>
    </form>
    </body>
</html>
