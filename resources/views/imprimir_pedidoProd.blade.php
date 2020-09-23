<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relatório Quantitativo - Rede de Orgânicos Osório</title>
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
            margin-top: 1rem;
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
                        <th colspan="4" style="background: #eee">RELATÓRIO QUANTITATIVO - {{@$pedido['prodnome']}}</th>
                    </tr>
                   
                    <tr>
                        <td>Total vendido</td>
                        <td>{{@$pedido['total']}}</td>
                    </tr>
                    
                    <tr>
                        <td>Descrição: </td>
                        <td >{{@$pedido['total']}} {{ preg_replace('/[0-9]+/', '', $pedido['descricao'])}}</td>
                    </tr>
                    <tr>
                        <td>Produtor: </td>
                        <td > {{@$pedido['produtornome']}}</td>
                    </tr>
                    <tr>
                        <td>Destino: </td>
                        <td > {{$pedido['destino']}}</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
        @endforeach
        <div class="container">

        Emitido em: {{date('d/m/Y H:i:s')}}
        </div>
    <script>
    document.addEventListener("DOMContentLoaded", function(event) {
        window.print();
    });
    </script>
   
</body>
</html>