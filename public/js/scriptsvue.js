Vue.component('card', {
  data() {
    return {
    	produto_codigo: '',
    	quantidade: '',
      unidadeDescricao: '',
      produtos: [],

      btnDisabled: false
    }
  },

  methods: {
    adicionarNaCesta(codigo, valor) {
        if(!this.quantidade) return;
        this.btnDisabled = true;
      	let prod = { 
          produto_codigo: codigo, 
          quantidade: this.quantidade, 
          unidade: this.unidadeDescricao,
          valor: valor
        };
      	let that = this;
      	axios.post('/cesta', {
    	  	 produto: prod
    	  })
    	  .then(response => {
    	    console.log(response);
    	    that.$root.$emit('pushed');
        })
    	  .catch(error => console.log(error))
        .then(() => {
          this.btnDisabled = false;
          this.quantidade = '';
        });
    },

    submitProdProduzido(event){
      console.log(this.produtos);
       if ($(event.target).is(':checked')) {

          this.produtos.push(event.target.id);
          axios.post('/produto_produzido', {
             produto_id: event.target.id
          })
          .then(function (response) {
            console.log(response);
          })
          .catch(function (error) {
            console.log(error);
          });

       } else {

          if (this.produtos.includes(event.target.id)) {
            axios.delete('/produto_produzido/' + event.target.id)
            .then(function (response) {
              console.log(response);
            })
            .catch(function (error) {
              console.log(error);
            });
          }

          let index = this.produtos.indexOf(event.target.id);
          if (index > -1) {
            this.produtos.splice(index, 1);
          }
       }
    }
  }
});

Vue.component('menucesta', {
  data() {
    return {
    	count: 0
    }
  },

  created(){
  	let that = this;
  	this.$root.$on('pushed',function() {
  		that.count++;
      // alert('Adicionado à Cesta');
      swal({
        title: "Adicionado à Cesta!",
        icon: "success"
      });
      $("input.qtdProd:text").val("");
  	});

  	axios.get('/cesta/getLength')
	  .then(function (response) {
	    that.count = response.data;
	  })
	  .catch(function (error) {
	    console.log(error);
	  });
  }
});

Vue.component('botaoadicionar', {
  data() {
    return {
      jaAdicionado: false,
      produtos: []
    }
  },

  methods: {
    addProdutoAoProdutor(produto_id){
      let that= this;
      axios.post('/addProdutoAoProdutor', {
           produto_id: produto_id
        })
        .then(function (response) {
          console.log(response);
          that.jaAdicionado = true;
          // that.$root.$emit('pushed');
        })
        .catch(function (error) {
          console.log(error);
        });
    }
  }
});


const app = new Vue({
    el: '#app',

    data: {
    	cesta: [],
    	// cestaFromDatabase: []
    },

    created(){
    	this.$root.$on('push', function(produto) {
	  		this.cesta.push(produto);
	  		this.$root.$emit('pushed');
	  	});
    },

    methods: {
    	test(){
    		console.log('test')
    	},
      submitProdutorEscolhas(){
        // console.log('this');
      }
    }
});
