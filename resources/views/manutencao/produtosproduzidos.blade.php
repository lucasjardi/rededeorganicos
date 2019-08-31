@extends('layouts.app')

@section('content')
<div style="background: rgba(255,255,255,0.2)">
    <div class="bg-white p-3">
        <produtosproduzidos inline-template>
            <div>
                <div class="row">
                    <div class="col">
                        <h1>Lista da Semana</h1>
                    </div>
                    <div class="col">
                        <div class="form-row" style="display:flex;justify-content:flex-end;margin-bottom: 2rem">
                            <div class="col">
                                <select @change="refresh" class="form-control" v-model="filters.sortBy">
                                    <option value="created_at">Mais recentes</option>
                                    <option value="oldests">Mais antigos</option>
                                    <option value="valor">Maior valor</option>
                                </select>
                            </div>
                            <div class="col">
                                <input @keydown.enter="refresh" v-model="filters.q" type="email" class="form-control" placeholder="Pesquisar">
                            </div>
                            <div class="col-1">
                                    <i @click="clear" class="fa fa-times fa-2x text-danger" style="cursor: pointer"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <th style="width:20%">Produto</th>
                        <th style="width:5%">Unidade</th>
                        <th style="width:20%">Preço</th>
                        <th style="width:20%">Produtor</th>
                        <th style="width:20%">Registro</th>
                        <th style="width:10%"></th>
                    </thead>
    
                    <tbody>
                        <tr v-for="product in products" :key="product.codigo">
                            <td style="width:20%" v-text="product.produto.nome"></td>
                            <td style="width:5%" v-text="product.unidade.descricao"></td>
                            <td style="width:20%" v-text="'R$ '+product.valor"></td>
                            <td style="width:20%" v-text="product.user.name"></td>
                            <td style="width:20%">@{{product.created_at | date}}</td>
                            <td style="width:10%">
                                <a class="text-danger" href="" @click.prevent="deleteProduct(product.codigo)"><i class="fa fa-times"></i> Remover</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </produtosproduzidos>
    </div>
</div>
@endsection