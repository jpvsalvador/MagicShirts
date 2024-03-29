<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfilePost;
use App\Http\Requests\PasswordPost;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Encomenda;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $metodos = ['VISA', 'MC', 'PAYPAL'];

        return view('users.Profile')
            ->withPageTitle('Perfil')
            ->with('user', auth()->user())
            ->withMetodos($metodos);
    }

    public function edit(ProfilePost $request) {

        $request->validated();
        if ($request->id == auth()->user()->id) {
            $user = auth()->user();
            
            $user->name = $request->nome;
            $user->email = $request->email;
            $user->cliente->nif = $request->nif;
            $user->cliente->endereco = $request->morada;
            $user->cliente->tipo_pagamento = $request->metodo_pagamento;
            $user->cliente->ref_pagamento = $request->ref_pagamento;
            $user->cliente->save();
        }
        else {
            $user = User::find($request->id);
            $user->name = $request->nome;
        }

        if ($request->hasFile('foto')) {
            Storage::delete('public/fotos/' . $user->foto_url);
            $path = $request->foto->store('public/fotos');
            $user->foto_url = basename($path);
        }

        $user->save();

        if ($request->id == auth()->user()->id) {
            return back()
            ->with('alert-msg', "Informação atualizada com sucesso!")
            ->with('alert-type', 'success');
        }
        else {
            return redirect()->route('Users')
            ->with('alert-msg', "Informação atualizada com sucesso!")
            ->with('alert-type', 'success');
        }

    }

    public function password_update(PasswordPost $request, User $user){

        if (Hash::check($request->password_atual, $user->password)) {
            $user->password = Hash::make($request->nova_password);
            $user->save();
        } else {
            return back()
            ->with('alert-msg', "Password incorreta!")
            ->with('alert-type', 'danger');
        }

        return back()
            ->with('alert-msg', "Password atualizada com sucesso!")
            ->with('alert-type', 'success');
    }

    public function destroy_foto()
    {
        $user = auth()->user();
        Storage::delete('public/fotos/' . $user->foto_url);
        $user->foto_url = null;
        $user->save();
        return back()
            ->with('alert-msg', "Informação atualizada com sucesso!")
            ->with('alert-type', 'success');
    }
}

