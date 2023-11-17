@component('mail::message')
# Import Products Via SFTP

@if(count($logs) > 0)
@foreach($logs as $key => $log)
<strong>{{ $key }} Summary:-</strong><br>
<ul>
<li>Login: {{$log['login']}}</li>
<li>Downloaded File: {{$log['downloaded']}}</li>
<li>Total recoreds: {{$log['total']}}</li>
<li>Update/Created recoreds: {{$log['updateOrCreate']}}</li>
<li>Discarded recoreds: {{$log['discarded']}}</li>
@if(!is_null($log['exception']))
	<li>Exception: {{$log['exception']}}</li>
@endif
</ul>

<br>
@endforeach
@else
No file found
@endif
@endcomponent
