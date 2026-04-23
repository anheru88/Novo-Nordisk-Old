//Vue.use(VueToast);
new Vue({
    el: '#app',
    data: {
        productsArray:[],
        productsNegotiated:[],
        productInfo:[],
        descInfo:[],
        productsList:[],

        dateOK:false,
        days:0,
        client:"",
        discount_text:"...",
        nota_text:"",
        discount:"",
        discount_approved:"-",
        pay_discount:"-",
        idProduct:0,
        productName:"",
        discountsxproduct:"-",
        discount_type_val:0,
        volumen_val:"",

        concept:false, // Verifica que haya seleccionado un concepto
        discount_type:false, // habilita ingreso de descuento
        newnota:false, // habilita el campo de nueva acalaratoria
        volumen:false, // Si es tru habilita los campos de unidades
        newconcept: false, /// Si es true habilita campo de nuevo concepto en lugar de las opciones del select
        addnota:false,

        // Lista de productos
        inputProducts:[], // Vector de productos agregadoa la negociacion
        listProducts:[], // Vector de ids de productos agregados a la negociacion
        prodPosition:"", // posicion del producto en el select dropdown


    },
    methods:{

        // NEGOCIACIONES

        getProductsClientQuota:function(){
            var urlProduct = '../getProductsClientQuota';
            //alert(this.client);
            axios.post(urlProduct,{
                idClient: this.client,
            }).then(response => {
                this.productsArray = response.data;
                for (let index = 0; index < this.productsArray.length; index++) {
                    this.productsList.push(this.productsArray[index].id_product);
                }
                console.log(this.productsList);
                this.errors = [];
            })
            .catch(function(error){
                this.test = error;
            })
        },

        getProductPayDiscount:function(){
            this.concept = false;
            this.discount_type = false;
            this.discount_approved = "-";
            $('#concepto').prop('selectedIndex',0);
            this.prodPosition = $("#product").prop('selectedIndex'); // captura la posicion en el select

            var urlProduct = '../getProductsClientDesc';
            this.idProduct = event.target.value;
            this.productName = $( "#product option:selected" ).text();

            axios.post(urlProduct,{
                idProduct:  this.idProduct,
                idClient:   this.client,
            }).then(response => {
                this.productInfo = response.data;
                this.pay_discount = this.productInfo.pay_discount;
                this.discountsxproduct = this.productInfo.totalDesc;
                this.errors = [];
            })
            .catch(function(error){
                this.test = error;
            })
        },

        addProduct:function(){

            if(event.target.value > 0){

                this.concept = true;
                seleccion = event.target.options[event.target.value].text;

                if(seleccion == "NUEVO" || seleccion == "nuevo"){
                    this.discount_text = "";
                    this.newconcept = true;
                }else{
                    this.discount_text = event.target.options[event.target.value].text;
                    this.newconcept = false;
                }

            }else{
                this.concept = false;
                this.discount_text = "...";
            }

        },

        addNota:function(){
            if(event.target.value > 0){
                seleccion = event.target.options[event.target.value].text;
                this.addnota = true;
                if(seleccion == "NUEVO" || seleccion == "nuevo"){
                    this.nota_text = "";
                    this.newnota = true;
                }else{
                    this.nota_text = event.target.options[event.target.value].text;
                    this.newnota = false;
                }
            }else{
                this.addnota = false;
            }

        },

        discountType:function(){

            this.discount_type = true;
            this.discount_type_val = event.target.value;
            this.calcProductQuota();
        },

        setVolumen:function(){
            this.volumen_val = event.target.value;

            if(this.volumen_val == "si"){
                this.volumen = true;
            }else{
                this.volumen = false;
            }

        },



        calcProductQuota:function(){
            var urlProduct = '../calcDiscount';
            axios.post(urlProduct,{
                idProduct:          this.idProduct,
                discount:           this.discount,
                pay_discount:       this.pay_discount,
                discount_val:       this.discount_type_val,
                client:             this.client,
                totalDesc:          this.discountsxproduct,
            }).then(response => {
                this.descInfo = response.data;
                //console.log(this.descInfo);
                this.discount_approved = this.descInfo['totalDesc']

                if(this.descInfo['done'] == 0){
                    this.discount = ""
                    Vue.$toast.open({
                        message:this.descInfo['msg'],
                        position: 'top-right',
                        type: 'error'
                    })
                }
                this.errors = [];
            })
            .catch(function(error){
                this.test = error;
            })
        },

        addProductsNegotiation:function(){

            concepto        = $('#concepto').val();
            observaciones   = $('#observaciones').val();
            cantidad        = $('#cantidad').val();
            unidades        = $('#unidades').val();

            if (this.idProduct == "" || concepto == "" || this.volumen_val == "" || this.discount_type_val == "" || this.discount == "") {
                Vue.$toast.open({
                    message:    'Los campos con * son obligatorios',
                    position:   'top-right',
                    type:       'error',
                });
                return;
            }

            productId = this.idProduct;
            this.listProducts.push(Number(productId));
            this.inputProducts.push({
                productId:          this.idProduct,
                productname:        this.productName,
                concepto:           concepto,
                conceptotext:       this.discount_text,
                observaciones:      observaciones,
                cantidad:           cantidad,
                unidades:           unidades,
                tipoDescuento:      this.discount_type_val,
                aclaracion:         this.nota_text,
                volumen:            this.volumen_val,
                descuento:          this.discount,
                descAcumulado:      this.discount_approved,
                prodPosition:       this.prodPosition,
            });

            $("#product option[value='"+this.idProduct+"']").remove();
            $('#modal-negocios').modal('toggle');
            this.restartModal();
       },

       restartModal:function(){
            $('#concepto').prop('selectedIndex',0);
            $('#product').prop('selectedIndex',0);
            $('#tipo_nota').prop('selectedIndex',0);
            $('#unidades').prop('selectedIndex',0);
            $('#observaciones').val("");
            $('#cantidad').val("");
            this.idProduct              = "";
            this.discount_type_val      = "";
            this.nota_text              = "";
            this.volumen_val            = "";
            this.discount               = "";
            this.discount_approved      = "";
            this.prodPosition           = "";
            this.concept                = false;
            this.discount_type          = false;
            this.newnota                = false;
            this.volumen                = false;
            this.newconcept             = false;
            this.discount               = "";
            this.discount_approved      = "-";
            this.pay_discount           = "-";
            this.discountsxproduct      = "-";
            this.discount_text          = "...";
       },

        /* Utility functions */
        sendCotizacion:function(event){
            if (this.inputProducts <= 0 )
            {
                Swal.fire(
                    'Alerta',
                    'Debe agregar un producto',
                    'warning',
                );
                event.preventDefault();
            }
        },

        formatPrice:function(value) {
            let val = (value/1).toFixed(0).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },

        dateFormat:function(date) {
            return moment(date, 'YYYY-MM-DD').format('DD/MM/YYYY');
        },

        removeProduct:function(rnum,prodPosition) {
            //console.log(this.inputProducts[rnum].vTotal);
            Swal.fire({
                title: 'Confirmación',
                text: "¿Esta seguro que desea eliminar este producto de la negociación?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar',
                cancelButtonText: 'No, cancelar',
              }).then((result) => {
                if (result.value) {
                    this.listProducts.splice(rnum,1);
                    this.inputProducts.splice(rnum,1);
                   // $('#product').append('<option value="'+this.productsArray[prodPosition-1].id_product+'">'+this.productsArray[prodPosition-1].productname+'</option>');
                    $("#product option").eq(prodPosition).before($("<option></option>").val(this.productsArray[prodPosition-1].id_product).text(this.productsArray[prodPosition-1].productname));
                }
              })

            //

        },

        setDate:function(){
            if(this.dateOK == false){
                $("#quota_date_ini").datepicker({ dateFormat: "y-m-d"}).datepicker("setDate", new Date());
                this.dateOK = true;
            }else{
                $("#quota_date_ini").val('');
                this.dateOK = false;
            }
        },

        setDays:function(){
            var tt = $("#quota_date_ini").val();
            var days = Number($("#days").val());
            var date = new Date(tt);
            var newdate = new Date(date);

            newdate.setDate(date.getDate() + days + 1);

            var dd = newdate.getDate();
            var mm = newdate.getMonth() + 1;
            var y = newdate.getFullYear();

            var someFormattedDate = y + '-' + mm + '-' + dd;
            //alert(someFormattedDate);
            $("#quota_date_end").datepicker({ dateFormat: "y-m-d"}).datepicker("setDate", someFormattedDate);
        },

        getDays:function(){

            var fechaini = new Date(String($("#quota_date_ini").val()));
            var fechafin = new Date(String($("#quota_date_end").val()));
            if(fechaini != "" && fechafin != ""){
                var diasdif= fechafin.getTime()-fechaini.getTime();
                var contdias = Math.round(diasdif/(1000*60*60*24));
                $("#days").val(contdias);
            }

            if($("#days").val() > 365){
                this.showTotalCot = true;
                this.authorizer = true;
            }else{
                this.showTotalCot = false;
                this.authorizer = false;
            }
        },

        numbervalidator:function(evt){
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            //console.log(charCode);
            if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
              evt.preventDefault();
            } else {
              return true;
            }
        }
    },
});



