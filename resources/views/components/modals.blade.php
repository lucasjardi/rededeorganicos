<div id="myModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Informações</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModalDetails()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="modalInfoParagraph"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="closeModalDetails()">ok</button>
        </div>
      </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="criarGrupo">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Novo Grupo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <div class="form-group">
            <label for="descricao">Nome do Grupo: </label>
            <input type="text" id="grupoDescricao" class="form-control" placeholder="Chás">
        </div>
      </div>
      <div class="modal-footer">
        <button id="btnSalvarGrupo" type="button" class="btn btn-primary">Salvar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="criarUnidade">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nova Unidade</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <div class="form-group">
            <label for="descricao">Nome da Unidade: </label>
            <input type="text" id="unidadeDescricao" class="form-control" placeholder="15ml">
        </div>
      </div>
      <div class="modal-footer">
        <button id="btnSalvarUnidade" type="button" class="btn btn-primary">Salvar</button>
      </div>
    </div>
  </div>
</div>