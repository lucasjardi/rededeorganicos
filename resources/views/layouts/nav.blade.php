<nav class="navbar navbar-dark bg-dark navbar-expand-lg fixed-top">
	<a class="navbar-brand" href="{{route('home')}}">Rede Orgânicos Osório</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>

	  <div class="collapse navbar-collapse" id="navbarSupportedContent">
	    <ul class="navbar-nav mr-auto">
	      <li class="nav-item {{ Request::is('home') || Request::is('/') || Request::is('retirar*') ? 'active' : '' }}">
	        <a class="nav-link" href="{{route('home')}}">Início</a>
	      </li>
	      @guest <!-- Visitantes -->
	      <li class="nav-item {{ Request::is('historia') ? 'active' : '' }}">
	        <a class="nav-link" href="/historia">História</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="#">Contato</a>
	      </li>
	      @endguest

	      <!-- Usuario Logado -->
	      @auth
			  @if(Auth::user()->ativo === 1) <!-- Usuario Ativo -->
				@if(Auth::user()->codNivel != 5 && Auth::user()->codNivel > 2)
					<li class="nav-item">
						<a class="nav-link {{ Request::is('produtos') ? 'active' : '' }}" href="{{ route('produtos') }}">Produtos</a>
					</li>
				@endif
		      @if(Auth::user()->codNivel === 5) <!-- Cliente -->
		      	<menucesta inline-template>
				  <li class="nav-item {{ Request::is('cesta') ? 'active' : '' }}">
			      	<a class="nav-link" href="{{ route('cesta') }}"><i class="fas fa-shopping-basket"></i> Cesta <span class="bg-white p-1 rounded text-dark font-weight-bold">@{{count}}</span></a>
			      </li>
				</menucesta>
			  @endif
			  @if(Auth::user()->codNivel === 4 || Auth::user()->codNivel === 5)
				<li class="nav-item {{ Request::is('user/pedidos') ? 'active' : '' }}">
					<a class="nav-link" href="{{ route('user.pedidos') }}">Pedidos</a>
				</li>
		      @endif
		      	@if(Auth::user()->codNivel < 4) <!-- Administradores -->
			      <li class="nav-item dropdown">
			        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			          Relatórios
			        </a>
			        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
					  <a class="dropdown-item disabled" href="#">Produtos</a>
			          <a class="dropdown-item" href="{{route('relatorios')}}">Pedidos</a>
			        </div>
			      </li>
			      <li class="nav-item dropdown">
			        <a class="nav-link dropdown-toggle {{ Request::is('manutencao/*') ? 'active' : '' }}" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			          Manuntenção
			        </a>
			        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
			          <a class="dropdown-item" href="{{ route('manutencao.solicitacoes') }}">Solicitações</a>
			          <a class="dropdown-item" href="{{ route('manutencao.produtos') }}">Produtos</a>
			          <a class="dropdown-item" href="{{ route('manutencao.pedidos') }}">Pedidos</a>
					  <a class="dropdown-item" href="{{ route('manutencao.locais') }}">Locais de Entrega</a>
					  <a class="dropdown-item" href="{{ route('manutencao.descontos') }}">Descontos</a>
					  <a class="dropdown-item" href="{{ route('manutencao.horariosacessocliente') }}">Horários de Acesso - Cliente</a>
					  <a class="dropdown-item" href="{{ route('manutencao.horariosacessoprodutor') }}">Horários de Acesso - Produtor</a>
					  <a class="dropdown-item" href="{{ route('manutencao.grupos') }}">Grupos</a>
					  <a class="dropdown-item" href="{{ route('manutencao.unidades') }}">Unidades de Medida</a>
					  <a class="dropdown-item" href="{{ route('manutencao.lista') }}">Lista da Semana</a>
			          @if(Auth::user()->codNivel < 3) <!-- Super Usuarios -->
			          <a class="dropdown-item" href="{{ route('manutencao.users') }}">Usuários</a>
			          @endif
			        </div>
			      </li>
				@endif
		    @endif
	      @endauth
	    </ul>

	    @auth
      	<ul class="navbar-nav">
	      	<li class="nav-item dropdown">
		        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		          {{ Auth::user()->name }}
		        </a>
		        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
		          @if(Auth::user()->codNivel > 3) <!-- Usuarios Comuns -->
		          <a class="dropdown-item" href="{{ route('users.informacoes') }}">Perfil</a>
		          @endif
		          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Sair</a>
		        </div>
		        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
		      </li>
	    </ul>
	    @endauth

	  </div>
</nav>