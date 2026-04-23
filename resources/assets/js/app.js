new Vue({
    el: '#app',
    created: function(){
       // this.showAlert();
    },
    /*mounted(){
        window.Echo.channel('novo-channel')
        .listen('OrderNotificationsEvent', (e) => {
            console.log('Ingresa pusher')
        });
    },*/
    data: {
        resultArray:[],
        dateOK:false,
        quantCheck:false,
        days:0,
        //////////////
        department:"",
        cities:[],
        city:undefined,
        selectedCity:undefined,
        //////////////
        usersBio:[],
        usersDiab:[],
        diabetesUser:"",
        bioUser:"",
        //////////////
        productid:"",
        products:[],
        productName:"",
        productNameModal:"",
        productList:"",
        productPrice:"",
        productPriceOld:"",
        productPriceUnits:"",
        tempPrice:"",
        desdeProd:"",
        hastaProd:"",
        level4:false,
        //////////////
        clients:[],
        client:"",
        clientCode:" ------ ",
        clientChannel:" ------ ",
        clientChannelID:"",
        //////////////
        authorizer:false,
        //////////////
        listProducts:[],
        listSelectedProducts:[],
        allSelected: false,
        //////////////
        showTotalCot:false,
        quantity:1,
        total:"",
        timeDiscount:"",
        priceDiscount:0,
        comDiscount:0,
        payPercent:"",
        payMethod:"",
        levelAuth:"",
        levelAuthQuota:0,
        levelDescIDQuota:0,
        levelDescID:"",
        appNum:"",
        alert:1,
        textDescription:"Descuento",
        descSymbol:"-",
        quotaValue:0,
        percentDiscount:0,
        payMethodSelect:"",
        totalQuota:0,
        inputProducts:[],
        quotaID:"",
        allProducts:[],
        usersList:[],
        userAuth:undefined,
        prodSelectedBlock:[],
        // Autorizer
        idAutorizer:"",
    },
    methods:{
        showAlert:function(){
            /*Swal.fire(
                'Alerta',
                'Ha superado el maximo de descuento permitido, por favor verifique',
                'warning',
            )*/
        },

        selectDepartment:function(){
           //alert(this.department);
           this.city = undefined;
            var urlCities = '../getCities';
            axios.post(urlCities,{
                department: this.department,
            }).then(response => {
                this.cities = response.data;
                //console.log(this.cities);
                this.errors = [];
            })
            .catch(function(error){
                this.test = error;
            })
        },

        changeCity:function(){
            //alert(event.target.value);
            this.department = event.target.value;
             var urlCities = '../../getCities';
             axios.post(urlCities,{
                 department: this.department,
             }).then(response => {
                 this.cities = response.data;
                 //console.log(this.cities);
                 this.errors = [];
             })
             .catch(function(error){
                 this.test = error;
             })
         },

        getUsersDiab:function(){
            var urlUsers = '../getUsers';
            //alert(this.diabetesUser);
            axios.post(urlUsers,{
                idUser: this.diabetesUser,
            }).then(response => {
                this.usersDiab = response.data;
                //console.log(this.usersDiab);
                this.errors = [];
            })
            .catch(function(error){
                this.test = error;
            })
        },

        getUsersBio:function(){
            var urlUsers = '../getUsers';
            axios.post(urlUsers,{
                idUser: this.bioUser,
            }).then(response => {
                this.usersBio = response.data;
               // console.log(this.usersBio);
                this.errors = [];
            })
            .catch(function(error){
                this.test = error;
            })
        },

        getProduct:function(product){
            var urlProduct = 'getProduct';
            axios.post(urlProduct,{
                idProduct: product,
            }).then(response => {
                this.products           = response.data;
                this.productNameModal   = this.products.product.prod_name;
                console.log(this.products.product.prod_name);
                this.errors = [];
            })
            .catch(function(error){
                this.test = error;
            })
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

        removeProduct:function(rnum) {
            //console.log(this.inputProducts[rnum].vTotal);
            Swal.fire({
                title: 'Confirmación',
                text: "¿Esta seguro que desea eliminar este producto de la cotización?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar',
                cancelButtonText: 'No, cancelar',
              }).then((result) => {
                if (result.value) {
                    this.totalQuota -= Number(this.inputProducts[rnum].vTotal);
                    this.listProducts.splice(rnum,1);
                    this.inputProducts.splice(rnum,1);
                }
              })

            //

        },

        removeClass:function(divID){
            $("#prod"+divID).removeClass("pulse");
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

        addAllProducts:function(){
           // console.log(this.prodSelectedBlock);
            replace = false;
            add = true;
            found = 0;
            for (let index = 0; index < this.listSelectedProducts.length; index++) {
                //const element = array[index];
                found = $.inArray(this.prodSelectedBlock[index], this.listProducts);
                if(found >= 0){
                    replace = true;
                }
            }

            if(replace){
                replace = false;
                Swal.fire({
                    title: 'Confirmación',
                    text: "¿Algunos productos ya han sido agregados desea reemplazarlos?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, reemplazar',
                    cancelButtonText: 'No, cancelar',
                  }).then((result) => {
                      resultado = result["dismiss"];
                      //console.log(resultado);
                        if(resultado == "cancel"){
                            add = false;
                            return;
                        }else{
                            for (let index = 0; index < this.listSelectedProducts.length; index++) {
                                this.addProductsBlock(this.listSelectedProducts[index], this.prodSelectedBlock[index]);
                            }
                        }
                });
            }else{
                if(add){
                    //console.log(add);
                    for (let index = 0; index < this.listSelectedProducts.length; index++) {
                        this.addProductsBlock(this.listSelectedProducts[index], this.prodSelectedBlock[index]);
                    }
                }
            }
        },

        addProductsBlock:function(idProduct, prodSelectedId){
             //alert(idProduct);
             /*console.log(prodSelectedId);
             console.log(this.listProducts);
             console.log(this.inputProducts);*/
             productId = Number(idProduct);
             found = $.inArray(prodSelectedId, this.listProducts);
             elem = this.listProducts.indexOf(prodSelectedId);
             //console.log(elem);
             if(found >= 0){
                found = 0;
                     //console.log(this.quantity);
                     this.inputProducts.splice(elem,1,{
                        productId:         this.allProducts[idProduct].productId,
                        productname:       this.allProducts[idProduct].productname,
                        quantity:          this.allProducts[idProduct].quantity,
                        uMinima:           this.allProducts[idProduct].uMinima,
                        vComercial:        this.allProducts[idProduct].vComercial,
                        vTotal:            this.allProducts[idProduct].vTotal,
                        symbol:            this.allProducts[idProduct].descSymbol,
                        dtoPrecio:         this.allProducts[idProduct].dtoPrecio,
                        dtoComercial:      this.allProducts[idProduct].dtoComercial,
                        fPago:             this.allProducts[idProduct].fPago,
                        fPagoID:           this.allProducts[idProduct].fPagoID,
                        productAuthLevel:  this.allProducts[idProduct].levelDescID,
                        productLevel:      this.allProducts[idProduct].levelAuth
                     });
                    setTimeout(function () { this.removeClass(this.productid) }.bind(this), 2000);
                    this.totalQuota = 0;
                    for (let index = 0; index < this.inputProducts.length; index++) {
                        this.totalQuota += Number(this.inputProducts[index].vTotal);
                    }

             } else{
                 this.listProducts.push(Number(prodSelectedId));
                 this.inputProducts.push({
                    productId:      this.allProducts[productId].productId,
                    productname:    this.allProducts[productId].productname,
                    quantity:       this.allProducts[productId].quantity,
                    uMinima:        this.allProducts[productId].uMinima,
                    vComercial:     this.allProducts[productId].vComercial,
                    vTotal:         this.allProducts[productId].vComercial,
                    dtoPrecio:      this.allProducts[productId].dtoPrecio,
                    dtoComercial:   this.allProducts[productId].dtoComercial,
                    fPago:          this.allProducts[productId].fPago,
                    fPagoID:        this.allProducts[productId].fPagoID
                 });
                 this.totalQuota = 0;
                for (let index = 0; index < this.inputProducts.length; index++) {
                    this.totalQuota += Number(this.inputProducts[index].vTotal);
                }
             }
             //console.log(this.inputProducts);
             this.totalQuota = 0;
             for (let index = 0; index < this.inputProducts.length; index++) {
                 this.totalQuota += Number(this.inputProducts[index].vTotal);
             }
        },

        selectAllProducts:function(){
            this.listSelectedProducts = [];
            this.prodSelectedBlock = [];
            checkboxes = document.getElementsByName('prods[]');
            //console.log(checkboxes);
            if(this.allSelected == false){
                for(i = 0; i < checkboxes.length; i++){
                    checkboxes[i].checked = true;
                    this.listSelectedProducts.push(Number(checkboxes[i].value));
                    this.prodSelectedBlock.push(Number(checkboxes[i].id));
                }
                this.allSelected = true;
            }else{
                for(var i in checkboxes){
                    checkboxes[i].checked = false;
                }
                this.listSelectedProducts = [];
                this.allSelected = false;
            }
            //console.log(this.listSelectedProducts);
            //console.log(this.allSelected);
            //console.log(this.prodSelectedBlock);
        },

        select:function(e){
            //this.listSelectedProducts = [];
            this.allSelected = false;
            $( "#select_all" ).prop( "checked", false );
            prodSelectedId = Number(e.target.id);
            prodSelected = Number(e.target.value);
            found = $.inArray(prodSelected, this.listSelectedProducts);
            if(found >= 0){
                this.listSelectedProducts.splice(found,1);
                this.prodSelectedBlock.splice(found,1);
            }else{
                this.listSelectedProducts.push(prodSelected);
                this.prodSelectedBlock.push(prodSelectedId);
            }
            //console.log(this.listSelectedProducts);
            //console.log(this.prodSelectedBlock);
        },

        // NEGOCIACIONES

        getClientProductsQuota(){
            var urlProduct = '../getClientProductsQuota';
                axios.post(urlProduct,{
                    idClient: this.client,
                }).then(response => {
                    this.resultArray = response.data;
                    console.log(this.resultArray);
                    this.errors = [];
                })
                .catch(function(error){
                    this.test = error;
                })
        },


        uncheck:function(){
            $( "#select_all" ).prop( "checked", false );
            checkboxes = document.getElementsByName('prods[]');
            for(var i in checkboxes){
                checkboxes[i].checked = false;
            }
            this.listSelectedProducts = [];
            this.allSelected = false;
        },

        activelevel4(){
            this.level4 = !this.level4;
            //console.log(this.level4);
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
        },

        // AUTORIZACIONES
        setAutorizer:function(){
            this.idAutorizer = $("#id_payterm option:selected").text();
        },

    },
});



