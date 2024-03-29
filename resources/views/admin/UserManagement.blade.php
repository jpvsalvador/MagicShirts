@extends('home.dashboard')
@section('title', 'Utilizadores')
@section('adminContent')
<div class="container">
    <h3>Filtro</h3>
    <div class="d-flex justify-content-start mb-4">
        <form action="{{route('Users.filter')}}" method="get">
            <button class="btn btn-dark btn-s mr-1" type="submit" name="tipo" value="A" aria-pressed="true">Administradores</button>
            <button class="btn btn-dark btn-s mr-1" type="submit" name="tipo" value="F" aria-pressed="true">Funcionários</button>
            <button class="btn btn-dark btn-s mr-1" type="submit" name="tipo" value="C" aria-pressed="true">Clientes</button>
        </form>
    </div>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th>User Id</th>
                <th>Nome</th>
                <th>Data Inscrição</th>
                <th>Tipo</th>
                <th>Permissões</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody class="bg-light">
            @foreach ($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->created_at}}</td>
                <td>{{$user->tipo}}</td>
                @if (is_null($user->deleted_at))
                    <td>
                        <button class="btn btn-success btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$user->id}}" aria-expanded="false" aria-controls="collapseOrder">
                            Alterar Permissões
                        </button>
                        <div class="collapse mb-n3 mt-2" id="collapse{{$user->id}}">
                            <form action="{{route('Users.permissions', ['user' => $user])}}" method="POST" class="form-group">
                            @csrf
                            @method('PATCH')
                                <div class="row">
                                    <select name="tipo" class="custom-select col">
                                        <option value="none" selected disabled hidden>Alterar permissões</option>
                                        @if ($user->tipo != "A")
                                            <option value="A">Administrador</option>
                                        @endif
                                        @if ($user->tipo != "F")
                                            <option value="F">Funcionário</option>
                                        @endif
                                        @if ($user->tipo != "C")
                                            <option value="C">Cliente</option>
                                        @endif
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm col ml-1">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </td>
                    @if ($user->tipo != 'C')
                        <td>
                            <a href="{{route('Users.edit', ['user' => $user])}}"><button type="button" class="btn btn-primary btn-sm launch">Editar</button></a>
                        </td>
                    @else
                        <td></td>
                    @endif

                    <td>
                        <form action="{{route('Users.block', ['user' => $user])}}" method="post">
                            @csrf
                            @method('PATCH')
                            @if ($user->bloqueado != "0")
                                <button type="submit" class="btn btn-primary btn-sm launch">Desbloquear</button>
                            @endif
                            @if ($user->bloqueado != "1")
                                <button type="submit" class="btn btn-primary btn-sm launch">Bloquear</button>
                            @endif
                        </form>
                    </td>
                    <td>
                        <form action="{{route('Users.delete', ['user' => $user])}}" method="post">
                            @csrf
                            @method("DELETE")
                                <input type="submit" class="btn btn-danger btn-sm" value="Apagar">
                        </form>
                    </td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <form action="{{route('Users.restore', $user)}}" method="POST">
                            @csrf
                            @method("PATCH")
                                <input type="text" name="user" hidden value="{{$user->id}}">
                                <input type="submit" class="btn btn-warning btn-sm" value="Restaurar">
                        </form>
                    </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
    {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection
