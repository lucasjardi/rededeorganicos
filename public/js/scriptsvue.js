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
          codProdutor: this.$refs.codProdutor.value,
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

Vue.component('produtosproduzidos', {
  data(){
    return{
      products: [],
      filters: {
        q: '',
        sortBy: 'created_at',
        sortDirection: 'desc'
      }
    }
  },
  mounted(){
    this.refresh();
  },
  filters: {
    date: function(value){
      let date = new Date(value);
      return date.getDate()+'/'+ (date.getMonth()+1) +'/'+date.getFullYear() + ' ' + date.getHours() + ':' + date.getMinutes();
    }
  },
  methods: {
    deleteProduct(id){
      document.querySelector('#loading-general').classList.add("is-visible");
      axios.delete('/produtos_produzidos/'+id)
      .then(res=>{
        let index = this.products.findIndex(prod=>prod.codigo==id);
        this.products.splice(index,1);
      })
      .catch(error=>console.log(error))
      .then(()=>document.querySelector('#loading-general').classList.remove("is-visible"));
    },
    refresh(){
      document.querySelector('#loading-general').classList.add("is-visible");
      axios.get('/produtos_produzidos',{params:{...this.filters}})
      .then(res=>this.products=res.data)
      .catch(error=>console.log(error))
      .then(()=>document.querySelector('#loading-general').classList.remove("is-visible"));
    },
    clear(){
      this.filters.q='';
      this.refresh();
    }
  },
})


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
