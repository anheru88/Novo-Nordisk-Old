//Vue.use(VueToast);
var vueF = new Vue({
    el: '#app',
    data: {
        productsArray: [],
        productsNegotiated: [],
        productInfo: [],
        descInfo: [],
        quotaProductsList: [],
        selectedProducts: [],
        productsAsistida: [],
        //
        fechaini: "",
        fechafin: "",
        //
        dateOK: false,
        days: 0,
        checkall : false,
        //
        client: "",
        nego_date_ini: "",
        nego_date_end: "",
        discount_text: "...",
        nota_text: "",
        discount: "",
        discount_price: "-",
        pay_discount: "-",
        idProduct: 0,
        productName: "",
        discount_acum: "-",
        discount_type_val: 0,
        volumen_val: "",
        new_concept:"",
        obs_concept:"",
        finded: "",


        // highlight de lista
        selected: 0,
        selected2: 'prod'+0,

        showProducts: false, // Habilita la visualizacion de productos
        showProductsNego: false, // Habilita la visualizacion de productos
        concept: false, // Verifica que haya seleccionado un concepto
        idConcept:"", // id del concepto seleccionado
        discount_type: false, // habilita ingreso de descuento
        newnota: false, // habilita el campo de nueva acalaratoria
        volumen: false, // Si es true habilita los campos de unidades
        newconcept: false, /// Si es true habilita campo de nuevo concepto en lugar de las opciones del select
        addnota: false,
        conceptval: "",
        showWarning: "", // mensaje de advertencia de mas de un año

        // Lista de productos
        inputProducts: [], // Vector de productos agregadoa la negociacion
        listProducts: [], // Vector de ids de productos agregados a la negociacion
        prodPosition: "", // posicion del producto en el select dropdown
        listDescAcumulated:[], // Array de descuento acumulados con id de producto.
        removeMasive:[],

        // Datos del cliente
        client_name: "",
        client_code: "",
        client_city: "",
        client_channel: "",
        client_idchannel:"",
        client_payterm: "",
        client_type: "",

        //Datos del producto en previsualizacion de cotizados
        c_idCot: "",
        c_vigencia_ini:"",
        c_vigencia_end:"",
        c_idProd: "",
        c_consecutive: "",
        c_prod: "",
        c_quantity: "",
        c_unitPrice: "",
        c_comPrice: "",
        c_totalQuota: "",
        c_priceDesc: "",
        c_payMethod: "",

        prod_pos: "",

        //Datos de productos negociados
        n_productname: "",
        n_tipoDescuento: "",
        n_descuento: "",
        n_descPrecio: "",
        n_descAcumulado: "",
        n_conceptotext: "",
        n_aclaracion: "",
        n_volumen: "",
        n_cantidad: "",
        n_unidades: "",
        n_observaciones: "",
        n_errores: "",
        n_errorNego: "",

        // Modal
        prod_name_modal: "",
        scale_name_modal: "",
        idScale_modal: "",
        idProd_modal: "",

        // Escalas
        scales: [],
        noScalesMsg: true,
        selectScale: undefined,

        // Niveles de autorizacion
        levelAuth: "",
        levelAuthQuota: "",
        listAuthLevels: [],

        // Cotización
        id_quotation: "",

        // Loading
        loading: true,

        //Id Negociacion
        idNegotiation: "",

        // tipos de Negociacion / Asistida - Individual
        individual : false,
        asistida: true,
        btnAsistidaEscala: true,
        btnAsistidaConcepto: false,

    },
    created: function(){
        this.getProductsClientNego();
        this.getProductsClientQuota();
    },
    methods: {

        getProductsClientNego:function(){
            this.client         = $("#id_client").val();
            this.idNegotiation  = $("#id_negotiation").val();
            this.fechaini       = $("#nego_date_ini").val();
            this.fechafin       = $("#nego_date_end").val();
            this.levelAuthQuota = $("#authlevel").val();
            this.loading        = true;
            var urlProduct      = '../../getProductsClientNego';
            axios.post(urlProduct, {
                idNegotiation: this.idNegotiation,
            }).then(response => {
                this.loading = false;
                if (response.data == "No data") {
                    this.inputProducts = [];
                    this.listProducts = [];
                    Swal.fire(
                        'Alerta',
                        'El cliente no tiene cotizaciones con productos vigentes',
                        'warning',
                    )
                } else {
                    this.productsAsistida = response.data;
                    for (let index = 0; index < this.productsAsistida.length; index++) {
                        if (index == 0) {
                            this.finded =  this.productsAsistida[index].finded;
                        }
                        this.listProducts.push(Number(this.productsAsistida[index].idProduct));
                        this.listAuthLevels.push(this.productsAsistida[index].authlevel);
                        this.listDescAcumulated.push({
                            productId: this.productsAsistida[index].idProduct,
                            idConcept: this.productsAsistida[index].idConcept,
                            descAcumulado: this.productsAsistida[index].desc_acum,
                            descuento: this.productsAsistida[index].desc,
                            tipoDescuento: this.productsAsistida[index].desc,
                        });
                        this.inputProducts.push({
                            idProduct:      this.productsAsistida[index].idProduct,
                            idConcept:      this.productsAsistida[index].idConcept,
                            productname:    this.productsAsistida[index].producto,
                            concepto:       this.productsAsistida[index].concepto,
                            conceptotext:   this.productsAsistida[index].conceptotext,
                            observaciones:  this.productsAsistida[index].observaciones,
                            cantidad:       this.productsAsistida[index].cantidad,
                            idUnidades:     this.productsAsistida[index].unidadesId,
                            unidades:       this.productsAsistida[index].unidades,
                            aclaracion:     this.productsAsistida[index].aclaracion,
                            volumen:        this.productsAsistida[index].volumen,
                            tipoDescuento:  this.productsAsistida[index].concepto,
                            descuento:      this.productsAsistida[index].desc,
                            descPrecio:     this.productsAsistida[index].desc,
                            descAcumulado:  this.productsAsistida[index].desc_acum,
                            productLevel:   this.productsAsistida[index].authlevel,
                            idQuotation:    this.productsAsistida[index].idQuotation,
                            idScale:        this.productsAsistida[index].idScale,
                            idScaleLvl:     this.productsAsistida[index].idScaleLvl,
                            prodPosition:   index,
                            visible:        this.productsAsistida[index].visible,
                            errorNego:      this.productsAsistida[index].msg,
                            finded:         this.productsAsistida[index].finded,
                            warning:        this.productsAsistida[index].warning
                        });
                    }
                }
                this.errors = [];
            })
            .catch(function (error) {
                this.test = error;
            })
        },
        // NEGOCIACIONES
        // Obtiene la información del cliente luego de ser seleccionado y cargado.
        getProductsClientQuota: function () {
            this.client         = $("#client").val();
            this.idNegotiation  = $("#id_negotiation").val();
            this.fechaini       = $("#nego_date_ini").val();
            this.fechafin       = $("#nego_date_end").val();
            var urlProduct = '../../getProductsClientQuota';
            this.loading = true;
            axios.post(urlProduct, {
                    idClient: this.client,
                    fechaini: this.fechaini,
                    fechafin: this.fechafin,
                }).then(response => {
                    this.loading = false;
                    if (response.data == "No data") {
                        this.inputProducts = [];
                        this.listProducts = [];
                        Swal.fire(
                            'Alerta',
                            'El cliente no tiene cotizaciones con productos vigentes',
                            'warning',
                        )
                    } else {
                        this.showProducts = true;
                        this.productsArray = response.data;
                        this.client_code        = this.productsArray[0].client_code;
                        this.client_city        = this.productsArray[0].client_city;
                        this.client_channel     = this.productsArray[0].client_channel;
                        this.client_idchannel   = this.productsArray[0].client_idchannel;
                        this.client_payterm     = this.productsArray[0].client_payterm;
                        this.client_type        = this.productsArray[0].client_type;
                        for (let index = 0; index < this.productsArray.length; index++) {
                            this.quotaProductsList.push(this.productsArray[index].id_product);
                        }

                        if(this.prodpos == undefined ){
                            this.previewProductQuota(0);
                        }else{
                            this.previewProductQuota(this.prodpos);
                        }
                    }
                    this.errors = [];
                })
                .catch(function (error) {
                    this.test = error;
                })

        },

        previewProductQuota: function (pos) {
            this.c_idCot        = this.productsArray[pos].id_quotation;
            this.c_vigencia_ini = this.productsArray[pos].date_ini;
            this.c_vigencia_end = this.productsArray[pos].date_end;
            this.c_idProd       = this.productsArray[pos].id_product;
            this.c_consecutive  = this.productsArray[pos].consecutive;
            this.c_prod         = this.productsArray[pos].productname;
            this.c_quantity     = this.productsArray[pos].quantity;
            this.c_unitPrice    = this.productsArray[pos].uMinima;
            this.c_comPrice     = this.productsArray[pos].vComercial;
            this.c_totalQuota   = this.productsArray[pos].vTotal;
            this.c_priceDesc    = this.productsArray[pos].dtoPrecio;
            this.c_payMethod    = this.productsArray[pos].fPago;
            this.c_scale        = this.productsArray[pos].scale;
            this.prodpos        = pos;
        },

        previewProductNego: function (pos) {
            this.n_productname      = this.inputProducts[pos].productname;
            this.n_tipoDescuento    = this.inputProducts[pos].tipoDescuento;
            this.n_descuento        = this.inputProducts[pos].descuento;
            this.n_descPrecio       = this.inputProducts[pos].totalDesc;
            this.n_descAcumulado    = this.inputProducts[pos].descAcumulado;
            this.n_conceptotext     = this.inputProducts[pos].conceptotext;
            this.n_aclaracion       = this.inputProducts[pos].aclaracion;
            this.n_volumen          = this.inputProducts[pos].volumen;
            this.n_cantidad         = this.inputProducts[pos].cantidad;
            this.n_unidades         = this.inputProducts[pos].unidades;
            this.n_observaciones    = this.inputProducts[pos].observaciones;
            this.n_errorNego        = this.inputProducts[pos].errorNego;
            this.finded             = this.inputProducts[pos].finded;
            this.warning            = this.inputProducts[pos].warning;
        },

        getProductsClientDesc: function () {
            this.concept = false;
            this.discount_type = false;
            this.discount_approved = "-";
            $('#concepto').prop('selectedIndex', 0);
            this.prodPosition = $("#product").prop('selectedIndex'); // captura la posicion en el select

            var urlProduct = '../../getProductsClientDesc';
            this.idProduct = event.target.value;
            this.productName = $("#product option:selected").text();
            axios.post(urlProduct, {
                    idProduct: this.idProduct,
                    idClient: this.client,
                    listDescAcumulated: this.listDescAcumulated, // lista actual de precios acumulados
                }).then(response => {
                    if(response.data == "false"){
                        Swal.fire(
                            'Alerta',
                            'El cliente no tiene escalas asignadas',
                            'warning',
                        )
                    }else{
                        this.productInfo        = response.data;
                        this.pay_discount       = this.productInfo.pay_discount;
                        this.quotedPrice        = this.productInfo.quotedPrice;
                        this.id_quotation       = this.productInfo.id_quotation;
                        this.clientChannel      = this.productInfo.clientChannel;
                        this.errors = [];
                    }
                })
                .catch(function (error) {
                    this.test = error;
                })
        },

        addProduct: function () {
            if (event.target.value > 0) {

                this.btnAsistidaConcepto = true;
                this.btnAsistidaEscala = false;

                this.concept = true;
                seleccion = event.target.options[event.target.selectedIndex].text;

                this.idConcept = event.target.value;
                this.discount_text = event.target.options[event.target.selectedIndex].text;
                this.newconcept = false;

                this.conceptval =  this.discount_text;
                this.discount = "";

            } else {
                this.concept = false;
                this.discount_text = "...";
                this.btnAsistidaConcepto = false;
                this.btnAsistidaEscala = true;
            }

            this.discount = 0;
            this.pay_discount = 0;
            this.discount_price = 0;
            this.discount_acum = 0;

        },

        addNota: function () {
            if (event.target.value > 0) {
                seleccion = event.target.options[event.target.value].text;
                this.addnota = true;
                if (seleccion == "NUEVO" || seleccion == "nuevo") {
                    this.nota_text = "";
                    this.newnota = true;
                } else {
                    this.nota_text = event.target.options[event.target.value].text;
                    this.newnota = false;
                }
            } else {
                this.addnota = false;

            }

        },

        discountType: function () {
            this.discount_type = true;
            this.discount_type_val = event.target.value;
            this.calcDiscount();
        },

        setVolumen: function () {
            this.volumen_val = event.target.value;
            if (this.volumen_val == "si") {
                this.volumen = true;
            } else {
                this.volumen = false;
            }

        },

        addSingleProductsAsistida:function(){
            checkboxAll = document.getElementById('select-all');
            checkboxAll.checked = false;
            this.checkall = false;
        },

        removeSingleProductsAsistida:function(){
            checkboxAll = document.getElementById('remove-all');
            checkboxAll.checked = false;
            this.checkall = false;
        },

        addAllProductsAsistida:function(name){
            checkboxes = document.getElementsByName(name);
            this.checkall = !this.checkall;
            for(var i=0; i<checkboxes.length; i++){
                if(checkboxes[i].type=='checkbox')
                checkboxes[i].checked=this.checkall;
            }
        },

        removeAllProductsAsistida:function(name){
            checkboxes = document.getElementsByName(name);
            this.checkall = !this.checkall;
            for(var i=0; i<checkboxes.length; i++){
                if(checkboxes[i].type=='checkbox')
                checkboxes[i].checked=this.checkall;
            }
        },
        // Negociacion asistida x escalas
        negociacionAsistida: function (name) {

            var checkBox = false;
            checkboxes = document.getElementsByName(name);

            for(var i=0; i<checkboxes.length; i++){
                if(checkboxes[i].checked)
                checkBox = true;
            }

            if(checkBox == false){
                Swal.fire(
                    'Alerta',
                    'Debe seleccionar por lo menos 1 producto para poder realizar el proceso',
                    'warning',
                )
                return;
            }else{

                for(var i=0; i<checkboxes.length; i++){
                    if(checkboxes[i].checked)
                    this.selectedProducts.push(parseInt(checkboxes[i].value));
                }
            }

            var urlQuery = '../../negociacionAsistida';
            axios.post(urlQuery, {
                idClient: this.client,
                fechaini: this.fechaini,
                fechafin: this.fechafin,
                clientChannel: this.client_idchannel,
                quotaProductsList: this.selectedProducts, // lista de los productos cotizados por el cliente
            }).then(response => {
                this.productsAsistida = response.data;
                if (response.data == "No escalas") {
                    Swal.fire(
                        'Alerta',
                        'El cliente no tiene escalas asignadas',
                        'warning',
                    )
                }
                else if(response.data == "No scales"){
                    Swal.fire(
                        'Alerta',
                        'Uno de los productos no tiene escalas asignadas',
                        'warning',
                    )
                } else {
                    this.listProducts = [];
                    this.listAuthLevels = [];
                    this.inputProducts = [];
                    this.listDescAcumulated = [];
                    this.selectedProducts = [];
                    this.levelAuth = 1;
                    for (let index = 0; index < this.productsAsistida.length; index++) {
                        this.listProducts.push(Number(this.productsAsistida[index].idProduct));
                        this.listAuthLevels.push(this.productsAsistida[index].authlevel);
                        this.listDescAcumulated.push({
                            productId: this.productsAsistida[index].idProduct,
                            idConcept: this.productsAsistida[index].idConcept,
                            descAcumulado: this.productsAsistida[index].desc_acum,
                            descuento: this.productsAsistida[index].desc,
                            tipoDescuento: this.productsAsistida[index].desc,
                        });
                        this.inputProducts.push({
                            idProduct:      this.productsAsistida[index].idProduct,
                            idConcept:      this.productsAsistida[index].idConcept,
                            productname:    this.productsAsistida[index].producto,
                            concepto:       this.productsAsistida[index].concepto,
                            conceptotext:   this.productsAsistida[index].concepto,
                            observaciones:  this.productsAsistida[index].observaciones,
                            cantidad:       this.productsAsistida[index].cantidad,
                            idUnidades:     this.productsAsistida[index].unidadesId,
                            unidades:       this.productsAsistida[index].unidades,
                            aclaracion:     this.productsAsistida[index].aclaracion,
                            volumen:        this.productsAsistida[index].volumen,
                            tipoDescuento:  this.productsAsistida[index].concepto,
                            descuento:      this.productsAsistida[index].desc,
                            descPrecio:     this.productsAsistida[index].desc,
                            descAcumulado:  this.productsAsistida[index].desc_acum,
                            productLevel:   this.levelAuth,
                            idQuotation:    this.productsAsistida[index].idQuotation,
                            idScale:        this.productsAsistida[index].idScale,
                            idScaleLvl:     this.productsAsistida[index].idScaleLvl,
                            prodPosition:   index,
                            visible:        this.productsAsistida[index].visible,
                            errorNego:      this.productsAsistida[index].msg,
                            finded:         this.productsAsistida[index].finded,
                            warning:        this.productsAsistida[index].warning
                        });
                    }
                }
                this.previewProductNego(0);
                this.setMaxLevel();
                this.errors = [];
            })
            .catch(function (error) {
                this.test = error;
            })
        },
        // Negociacion x producto individual
        calcDiscount: function () {
            var urlProduct = '../../calcDiscount';
            if(this.discount > 0){
                axios.post(urlProduct, {
                    fechaini:           this.fechaini,
                    idProduct:          parseInt(this.idProduct),
                    discount:           this.discount,
                    pay_discount:       this.pay_discount,
                    discount_val:       this.discount_type_val,
                    client:             this.client,
                    quotedPrice:        this.quotedPrice,
                    concept:            this.conceptval,
                    idConcept:          parseInt(this.idConcept),
                    listDescAcumulated: this.inputProducts, // lista actual de precios acumulados
                    clientChannel:      this.clientChannel,
                    newConcept:         this.discount_text,
                }).then(response => {
                    this.descInfo = response.data;
                    console.log(this.descInfo);
                    if (this.descInfo['done'] == 0) {
                        this.discount = 0
                        this.discount_price = 0;
                        this.discount_acum  = 0;
                        this.discount_approved = this.pay_discount;
                        toastr.options = {
                            closeButton: false,
                            progressBar: true,
                            newestOnTop: true,
                        };
                        toastr.error(this.descInfo['msg']).css("width", "auto")
                    } else {
                        this.discount_price = this.descInfo['discountPrice'];
                        this.discount_acum  = this.descInfo['discountAcum'];
                    }
                    this.errors = [];
                })
                .catch(function (error) {
                    this.test = error;
                })
            }
        },
        // Negociacion asistida x desceunto
        negociacionAsistidaxConcepto:function(name){
            var urlQuery = '../../negociacionAsistidaxConcepto';
            var concepto =  document.getElementById('conceptoIn').value;
            this.selectedProducts = [];

            checkboxes = document.getElementsByName(name);
            for(var i=0; i<checkboxes.length; i++){
                if(checkboxes[i].checked)
                this.selectedProducts.push(parseInt(checkboxes[i].value));
            }

            if(this.selectedProducts.length == 0){
                Swal.fire(
                    'Alerta',
                    'Debe seleccionar por lo menos 1 producto para poder realizar el proceso',
                    'warning',
                )
                return;
            }

            if (this.discount == "") {
                Swal.fire(
                    'Alerta',
                    'Debe agregar un tipo y valor de descuento',
                    'warning',
                )
                return;
            }
            axios.post(urlQuery, {
                client:             this.client,
                concept:            this.conceptval,
                discount:           this.discount,
                discount_val:       this.discount_type_val,
                fechaini:           this.fechaini,
                idClientChannel:    this.client_idchannel,
                idConcept:          concepto,
                idQuotation:        this.c_idCot,
                listDescAcumulated: this.inputProducts, // lista actual de precios acumulados
                obsConcept:         this.obs_concept,
                nota:               this.nota_text,
                pay_discount:       this.pay_discount,
                quotaProductsList:  this.selectedProducts, // lista de los productos cotizados por el cliente
                quotedPrice:        this.quotedPrice,
            }).then(response => {
                this.productsAsistida = response.data;
                console.log(this.productsAsistida);
                if (response.data == "No escalas") {
                    Swal.fire(
                        'Alerta',
                        'El cliente no tiene escalas asignadas',
                        'warning',
                    )
                }
                else if(response.data == "No data"){
                    Swal.fire(
                        'Alerta',
                        'Las escalas ya fueron asignadas en otra negociación',
                        'warning',
                    )
                }
                else if(response.data == "No scales"){
                    Swal.fire(
                        'Alerta',
                        'Uno de los productos no tiene escalas asignadas',
                        'warning',
                    )
                } else {
                    this.levelAuth = 1;
                    for (let index = 0; index < this.productsAsistida.length; index++) {
                        this.listProducts.push(Number(this.productsAsistida[index].idProductDiv));
                        this.listAuthLevels.push(this.productsAsistida[index].authLevel);
                        this.listDescAcumulated.push({
                            productId:      this.productsAsistida[index].idProduct,
                            idConcept:      this.productsAsistida[index].idConcept,
                            descAcumulado:  this.productsAsistida[index].desc_acum,
                            descuento:      this.productsAsistida[index].desc,
                            tipoDescuento:  this.productsAsistida[index].tDiscount,
                        });
                        this.inputProducts.push({
                            idProduct:      this.productsAsistida[index].idProduct,
                            idConcept:      this.productsAsistida[index].idConcept,
                            idQuotation:    this.productsAsistida[index].idQuotation,
                            productname:    this.productsAsistida[index].producto,
                            conceptotext:   this.productsAsistida[index].concepto,
                            observaciones:  this.productsAsistida[index].observaciones,
                            aclaracion:     this.productsAsistida[index].aclaracion,
                            volumen:        this.productsAsistida[index].volumen,
                            cantidad:       this.productsAsistida[index].cantidad,
                            idUnidades:     this.productsAsistida[index].unidadesId,
                            tipoDescuento:  this.productsAsistida[index].tDiscount,
                            descuento:      this.productsAsistida[index].desc,
                            descPrecio:     this.productsAsistida[index].desc,
                            descAcumulado:  this.productsAsistida[index].desc_acum,
                            productLevel:   this.productsAsistida[index].authLevel,
                            idQuotation:    this.productsAsistida[index].idQuotation,
                            idScale:        "",
                            idScaleLvl:     "",
                            prodPosition:   index,
                            errorNego:      this.productsAsistida[index].error,
                            visible:        this.productsAsistida[index].visible,
                            finded:         this.productsAsistida[index].finded,
                            warning:        this.productsAsistida[index].warning,
                        });
                    }
                }
                this.restartModal();
                this.previewProductNego(0);
                this.setMaxLevel();
                this.errors = [];
            })
            .catch(function (error) {
                this.test = error;
            })
        },

        addProductsNegotiation: function () {

            concepto = $('#concepto').val();

            if($('#observaciones').val()){
                observaciones = $('#observaciones').val();
            }else{
                observaciones = "N/A";
            }

            if($('#cantidad').val()){
                cantidad = $('#cantidad').val();
                unidades = $('#unidades').val();
            }else{
                cantidad = "N/A";
                unidades = "";
            }
            if (this.idProduct == "" || concepto == "" || this.volumen_val == "" || this.discount_type_val == "" || this.discount == "") {
                toastr.error('Los campos con * son obligatorios').css("width", "auto")
                return;
            }

            productId = this.idProduct;
            this.listProducts.push(Number(productId));

            this.levelAuth = this.descInfo['authLevel'];
            this.listAuthLevels.push(this.levelAuth);

            this.listDescAcumulated.push({
                productId:      this.idProduct,
                idConcept:      this.idConcept,
                descAcumulado:  this.discount_approved,
                descuento:      this.discount,
                tipoDescuento:  this.discount_type_val,
            })

            this.errorNego = [];

            this.inputProducts.push({
                idProduct:      this.idProduct,
                idConcept:      this.idConcept,
                productname:    this.productName,
                concepto:       concepto,
                conceptotext:   this.discount_text,
                observaciones:  observaciones,
                cantidad:       cantidad,
                idUnidades:     unidades,
                unidades:       unidades,
                aclaracion:     this.nota_text,
                volumen:        this.volumen_val,
                tipoDescuento:  this.discount_type_val,
                descuento:      this.discount,
                descPrecio:     this.discount_price,
                descAcumulado:  this.discount_acum,
                productLevel:   this.levelAuth,
                idQuotation:    this.id_quotation,
                errorNego:      this.errorNego,
                idScale:        "",
                idScaleLvl:     "",
                prodPosition:   this.listProducts.length-1,
                visible:        "SI",
                warning:        this.descInfo['warning'],
            });

            this.restartModal();
            this.previewProductNego(this.listProducts.length - 1);
            this.setMaxLevel();
            setTimeout(function() {
                var idScroll    = $('#scroll-prods');
                var height      = idScroll[0].scrollHeight;
                idScroll.scrollTop(height);
            },500);
            var positionScroll = this.listProducts.length-1;
            this.selected2 = "prod"+positionScroll;
            //console.log(this.inputProducts);
        },

        setMaxLevel: function () {
            if (this.listAuthLevels.length > 0) {
                this.levelAuthQuota = this.getMaxOfArray(this.listAuthLevels);
            } else {
                this.levelAuthQuota = 1;
            }
            var diasdif = $("#days").val();

            if(this.levelAuthQuota == 1){
                if(diasdif > 365){
                    this.levelAuth = 2;
                    this.levelAuthQuota = this.levelAuth;
                }
            }

            if(diasdif > 365){
                this.showWarning = 1;
            }else{
                this.showWarning = "";
            }
            console.log(this.levelAuthQuota);
        },

        sendNegotiation: function (event) {
            event.preventDefault();
            if (this.inputProducts <= 0) {
                Swal.fire(
                    'Alerta',
                    'Debe añadir un descuento negociado',
                    'warning',
                );
            }else{
                this.saveForm();
            }
        },

        removeProduct: function (rnum, prodPosition) {
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
                    this.listProducts.splice(rnum, 1);
                    this.inputProducts.splice(rnum, 1);
                    this.listAuthLevels.splice(rnum, 1);
                    this.listDescAcumulated.push(rnum, 1);
                    this.setMaxLevel();
                    if(this.listAuthLevels.length > 0){
                        this.previewProductNego(0);
                    }else{
                        this.levelAuth = "";
                    }
                    $( ".productsNeg" ).prop( "checked", false );
                    $( "#remove-all" ).prop( "checked", false );
                    this.checked = false;
                }
            })
        },

        removeProductMasive: function (name){
            var prod = false;
            checkboxes = document.getElementsByName(name);
            for(var i=0; i<checkboxes.length; i++){
                if(checkboxes[i].checked)
                prod = true
            }
            if(prod){
                Swal.fire({
                    title: 'Confirmación',
                    text: "¿Esta seguro que desea eliminar todos los productos de la negociación?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, eliminar',
                    cancelButtonText: 'No, cancelar',
                }).then((result) => {
                    if (result.value) {
                        checkboxes = document.getElementsByName(name);
                        for(var i=0; i<checkboxes.length; i++){
                            if(checkboxes[i].checked)
                            this.removeMasive.push(parseInt(checkboxes[i].value));
                        }
                        for (var i = this.removeMasive.length -1; i >= 0; i--){
                            this.listProducts.splice(this.removeMasive[i], 1);
                            this.inputProducts.splice(this.removeMasive[i], 1);
                            this.listAuthLevels.splice(this.removeMasive[i], 1);
                            this.listDescAcumulated.push(this.removeMasive[i], 1);
                        }
                        this.setMaxLevel();
                        if(this.listAuthLevels.length > 0){
                            this.previewProductNego(0);
                        }else{
                            this.levelAuth = "";
                        }
                        $( ".productsNeg" ).prop( "checked", false );
                        $( "#remove-all" ).prop( "checked", false );
                        this.n_tipoDescuento    = "";
                        this.n_descuento        = "";
                        this.n_descAcumulado    = "";
                        this.n_conceptotext     = "";
                        this.n_aclaracion       = "";
                        this.n_volumen          = "";
                        this.n_observaciones    = "";
                        this.n_errorNego        = "";
                        this.finded             = "";
                    }
                    this.removeMasive = [];
                });
            }else{
                Swal.fire({
                    title: 'Alerta',
                    text: "Debe seleccionar por lo menos un producto",
                    type: 'warning',
                    showCancelButton: false,
                })
            }
        },

        showProduct: function (rnum, prodPosition) {
            visible = this.inputProducts[rnum]['visible'];
            if(visible == "SI"){
                this.inputProducts[rnum]['visible'] = "NO";
            }else{
                this.inputProducts[rnum]['visible'] = "SI";
            }
        },

        /* Save form */
        saveForm: function(){
            var urlQuery = '../../updateNegotiation';
            axios.post(urlQuery,{
                idNegotiation: parseInt(this.idNegotiation),
                authLevel: this.levelAuthQuota,
                products: this.inputProducts
                }).then(response => {
                    console.log(response.data);
                    if (response.data != "") {
                        $( "#sendFiles").delay(500).submit();
                    } else {
                        toastr.options = {
                            "closeButton": false,
                            "progressBar": true,
                            "newestOnTop": false
                        };
                        toastr.error('Existio un error al guardar la negociación, intentelo de nuevo').css("width", "auto")
                    }
                this.errors = [];
            })
            .catch(function (error) {
                this.test = error;
            })
        },
        /* Utility functions */

        restartModal: function () {
            $('#concepto').prop('selectedIndex', 0);
            $('#conceptoIn').prop('selectedIndex', 0);
            $('#product').prop('selectedIndex', 0);
            $('#tipo_nota').prop('selectedIndex', 0);
            $('#unidades').prop('selectedIndex', 0);
            $('#observaciones').val("");
            $('#cantidad').val("");
            this.idProduct = "";
            this.discount_type_val = "";
            this.nota_text = "";
            this.volumen_val = "";
            this.discount = "";
            this.discount_approved = "";
            this.prodPosition = "";
            this.concept = false;
            this.discount_type = false;
            this.newnota = false;
            this.volumen = false;
            this.newconcept = false;
            this.discount = "";
            this.discount_approved = "-";
            this.pay_discount = "-";
            this.discountsxproduct = "-";
            this.discount_text = "...";
            this.asistida = true;
            this.individual = false;
            this.concept = false;
            this.discount_text = "...";
            this.btnAsistidaConcepto = false;
            this.btnAsistidaEscala = true;
            this.obs_concept = ""

        },

        formatPrice: function (value) {
            let val = (value / 1).toFixed(0).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },

        dateFormat: function (date) {
            return moment(date, 'YYYY-MM-DD').format('DD/MM/YYYY');
        },

        setDate: function () {
            if (this.dateOK == false) {
                $("#quota_date_ini").datepicker({
                    dateFormat: "y-m-d"
                }).datepicker("setDate", new Date());
                this.dateOK = true;
            } else {
                $("#quota_date_ini").val('');
                this.dateOK = false;
            }
            this.getDays();
        },

        setDays: function () {
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
            $("#quota_date_end").datepicker({
                dateFormat: "y-m-d"
            }).datepicker("setDate", someFormattedDate);
            this.getDays();
        },

        getDays: function () {
            this.fechaini = new Date(String($("#quota_date_ini").val()));
            this.fechafin = new Date(String($("#quota_date_end").val()));
            if (this.fechaini != "" && this.fechafin != "") {
                var diasdif = this.fechafin.getTime() - this.fechaini.getTime();
                var contdias = Math.round(diasdif / (1000 * 60 * 60 * 24));
                $("#days").val(contdias);
            }

            if ($("#days").val() > 365) {
                this.setMaxLevel();
            } else {
                this.setMaxLevel();
            }
        },

        numbervalidator: function (evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
                evt.preventDefault();
            } else {
                return true;
            }
        },

        getMaxOfArray: function(numArray) {
            return Math.max.apply(null, numArray);
        },

        showTab: function(tab){

            this.restartModal();
            if(tab == "asistida"){
                this.asistida = true;
                this.individual = false;
            }else{
                this.asistida = false;
                this.individual = true;
            }

        },
    },
});
