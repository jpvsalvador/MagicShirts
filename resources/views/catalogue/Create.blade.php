@extends('template')
@section('title', 'Criar Estampa')
@section('content')
    <div class="container">
        <form method="POST" action="{{route('Stamps.store')}}" class="form-group" enctype="multipart/form-data">
            @csrf
            @include('catalogue.partials.create-edit')
            <div class="form-group text-right">
                <button type="submit" class="btn btn-success" name="ok">Criar</button>
                <a href="{{url()->previous()}}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
