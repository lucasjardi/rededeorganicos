<?php

use Illuminate\Database\Seeder;

class PedidosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Pedido::class, 50)->create()->each(function ($pedido) {
            $pedido->itens()->save(factory(App\ItemPedido::class)->make([
                'codPedido' => $pedido->codigo,
                'valorTotal' => $pedido->valor
            ]));
        });
    }
}