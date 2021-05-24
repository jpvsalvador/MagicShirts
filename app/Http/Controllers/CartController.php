<?php

namespace App\Http\Controllers;

use App\Models\Tshirt;
use App\Models\Estampa;
use Illuminate\Http\Request;
use App\Models\Cor;
use App\Models\Preco;
use App\Http\Requests\ProductPost;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $listaTamanhos = ['XS', 'S', 'M', 'L', 'XL'];
        $listaCores = Cor::pluck('nome', 'codigo');
        $precoEstampa = Preco::find(1);
        $carrinho = $request->session()->get('carrinho', []);
        foreach($carrinho as $row) {
            $listaEstampas[] = Estampa::where('id', $row['estampa_id'])->pluck('cliente_id', 'imagem_url');
        }

        //dd(session('carrinho') ?? []);
        return view('orders.Cart')
            ->with('pageTitle', 'Carrinho de compras')
            ->with('carrinho', session('carrinho') ?? [])
            ->withTamanhos($listaTamanhos)
            ->withCores($listaCores)
            ->withPreco($precoEstampa)
            ->withEstampas($listaEstampas);
    }

    public function store_tshirt(ProductPost $request)
            ->withPreco($precoEstampa);
    }

    public function store_tshirt(Request $request, Tshirt $tshirt)
    {
        $request->validated();
        //dd($validated_data);
        $carrinho = $request->session()->get('carrinho', []);
        $carrinho[] = [
            'quantidade' => $request->quantidade,
            'estampa_id' => $request->estampa_id,
            'cor_codigo' => $request->cor_codigo,
            'tamanho' => $request->tamanho,
            'preco_un' => $request->preco_un,
        ];
        $request->session()->put('carrinho', $carrinho);
        return back()
            ->with('alert-msg', 'Foi adicionada uma tshirt carrinho!')
            ->with('alert-type', 'success');
    }

    public function update_tshirt(ProductPost $request)
    {
        $request->validate();
        $carrinho = $request->session()->get('carrinho', []);
        $quantidade = $carrinho[$request->id]['quantidade'] ?? 0;
        $quantidade += $request->quantidade;
        if ($request->quantidade < 0) {
            $msg = 'Foram removidas ' . -$request->quantidade . ' tshirts! Quantidade de tshirts atuais = ' .  $quantidade;
        } elseif ($request->quantidade > 0) {
            $msg = 'Foram adicionadas ' . $request->quantidade . ' tshirts! Quantidade de tshirts atuais = ' .  $quantidade;
        }
        if ($quantidade <= 0) {
            unset($carrinho[$request->id]);
            $msg = 'Foram removidas todas as tshirts';
        } else {
            $carrinho[$request->id] = [
                'id' => $request->id,
                'quantidade' => $quantidade,
                'id_encomenda' => $request->id_encomenda,
                'estampa_id' => $request->estampa_id,
                'cor_codigo' => $request->cor_codigo,
                'tamanho' => $request->tamanho,
                'preco_un' => $request->preco_un
            ];
        }
        $request->session()->put('carrinho', $carrinho);
        return back()
            ->with('alert-msg', $msg)
            ->with('alert-type', 'success');
    }

    public function destroy_tshirt(Request $request, Tshirt $tshirt)
    {
        $carrinho = $request->session()->get('carrinho', []);
        if (array_key_exists($tshirt->id, $carrinho)) {
            unset($carrinho[$tshirt->id]);
            $request->session()->put('carrinho', $carrinho);
            return back()
                ->with('alert-msg', 'Foram removidas todas tshirts')
                ->with('alert-type', 'success');
        }
        return back()
            ->with('alert-msg', 'A T-shirt já não estava no carrinho!')
            ->with('alert-type', 'warning');
    }

    public function store(Request $request)
    {
        dd(
            'Place code to store the shopping cart / transform the cart into a sale',
            $request->session()->get('carrinho')
        );
    }

    public function destroy(Request $request)
    {
        $request->session()->forget('carrinho');
        return back()
            ->with('alert-msg', 'Carrinho foi limpo!')
            ->with('alert-type', 'danger');
    }
}