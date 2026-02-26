<!DOCTYPE html>
<html>
<head>

<title>Translations UI</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
padding:40px;
}

.card{
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

table{
width:100%;
border-collapse:collapse;
}

th,td{
padding:10px;
border:1px solid #ddd;
}

input{
width:100%;
padding:6px;
}

button{
padding:8px 15px;
background:#28a745;
color:white;
border:none;
border-radius:5px;
}

.add-btn{
background:#007bff;
}

</style>

</head>
<body>

<div class="card">

<h2>Laravel Translations UI</h2>

@if(session('success'))
<p style="color:green">{{session('success')}}</p>
@endif


<form method="POST" action="/translations/add">

@csrf

<input type="text" name="key" placeholder="New Key" required>

<button class="add-btn">Add Key</button>

</form>


<br>


<form method="POST" action="/translations/save">

@csrf

<table>

<tr>

<th>Key</th>

@foreach($locales as $locale)

<th>{{ strtoupper($locale) }}</th>

@endforeach

</tr>


@foreach($translations as $key=>$items)

<tr>

<td>{{ $key }}</td>

@foreach($locales as $locale)

<td>

<input type="text"
name="translations[{{$key}}][{{$locale}}]"
value="{{ optional($items->where('locale',$locale)->first())->value }}">

</td>

@endforeach

</tr>

@endforeach


</table>


<br>

<button type="submit">Save Translations</button>

</form>

</div>

</body>
</html>