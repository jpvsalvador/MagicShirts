@extends('template')
@section('content')
<div class="adminMenu">

<a href="{{route('Dashboard')}}">Dashboard</a>
<a href="{{route('Orders')}}">Encomendas</a>
<<<<<<< Updated upstream
<a href="{{route('Users')}}">Utilizadores</a>
<a href="{{route('Stamps')}}">Estampas</a>
=======
<a href="">Utilizadores</a>
<a href="#">Estampas</a>
>>>>>>> Stashed changes
<a href="#">Cores</a>
</div>
<div class="adminContent">
@yield("adminContent")
</div>
@endsection
