<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalhes Pedido - Rede de Orgânicos Osório</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-style: solid;
            border-collapse: collapse;
            width: 50vw;
        }
        th, td {
            padding: 5px 15px 5px 15px;
            text-align: left;
        }
        .container{
            margin-top: 4rem;
            width: 100vw;
            display: flex;
            justify-content: center;
        }
    </style>

    <style type="text/css" media="print">
        table{
            width: 90vw;
        }
    </style>
</head>
<body>
    <h4 style="position: fixed; top : 0; right: 3rem"><button onclick="window.print()">IMPRIMIR</button></h4>
   
        @foreach ($pedidos as $pedido)
        <div class="container">
            <table class="table">
                <tbody>
                    <tr>
                        <th colspan="4" style="background: #eee">REDE DE ORGÂNICOS DE OSÓRIO - DETALHES DO PEDIDO</th>
                    </tr>
                    <tr>
                        <th>Número: {{$pedido->codigo}}</th>
                        <td colspan="2">Data: @datetime($pedido->dataPedido)</td>
                    </tr>
                    <tr>
                        <td>Status: {{$pedido->st->descricao}}</td>
                        <td colspan="2">Destino: {{$pedido->destino->descricao}}</td>
                    </tr>
                    <tr>
                        <th colspan="4" style="background: #eee">Cliente</th>
                    </tr>
                    <tr>
                        <td>Nome: {{$pedido->usuario->name}}</td>
                        <td colspan="2">E-mail: {{$pedido->usuario->email}}</td>
                    </tr>
                    <tr>
                        <td>CPF: {{$pedido->cliente->cpf}}</td>
                        <td>Telefone: {{$pedido->cliente->telefone}}</td>
                        <td>Cidade: {{$pedido->cliente->cidade->descricao}}</td>
                    </tr>
                    <tr>
                        <td colspan="4">Endereço: {{$pedido->cliente->endereco}}</td>
                    </tr>
                    <tr>
                        <th colspan="4" style="background: #eee">Itens do Pedido</th>
                    </tr>
                    @php $subTotal = 0; @endphp
                    @foreach ($pedido->itens as $item)
                        <tr><td colspan="3">{{$item->descricao}} - R$ @dinheiro($item->valorTotal)</td></tr>
                        @php $subTotal += $item->valorTotal; @endphp
                    @endforeach
                    <tr><td colspan="3">Subtotal: R$ @dinheiro($subTotal)</td></tr>
                    @if($subTotal > $pedido->valor) <tr><td colspan="3">Descontos: R$ @dinheiro($subTotal - $pedido->valor)</td></tr> @endif
                    <tr><th colspan="3" style="background: #eee">Total: R$ @dinheiro($pedido->valor)</th></tr>
                    <tr><td colspan="3" style="text-align: right;color:#363636">Emitido em: {{date('d/m/Y H:i:s')}}</td></tr>
                </tbody>
            </table>
        </div>
        @endforeach


    <script>
    document.addEventListener("DOMContentLoaded", function(event) {
        window.print();
    });
    </script>
</body>
</html>