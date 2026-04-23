new Vue({
    el: '#app',
    created: function(){
        //this.showLevel();
    },
    data: {
        editLogo:false,
        levelAuth:false,
        levelCheck:"",
        answer:"",
        res:"",
        approved:"",
        sendCot:"true",
    },
    methods:{
        sendEmail:function(id){
            console.log(id);
        },
        rejectform:function(e){
            Swal.fire({
                title: '¿Esta seguro de aprobar la lista de precios?',
                text: 'Escriba el motivo de rechazo de la lista',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                input: 'textarea',
                confirmButtonText: 'Rechazar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {
                    this.answer = result.value;
                    setTimeout(() => this.$refs.rejected.submit(), 500);
                }

            })

            e.preventDefault();
        },
        acceptform:function(e){
            Swal.fire({
                title: '¿Esta seguro de aprobar la lista de precios?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Aprobar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {
                    this.$refs.approved.submit()
                }
            })
            e.preventDefault();
        },

        preQuota:function(e){
            e.preventDefault();
            var term;
            var autorizers =  $('#autorizadores').val();
            var doc_lvl = $('#doc_lvl').val();
            console.log(autorizers);
            var url = "../validateAutorizers";
            if(this.approved == 3){
                term = "aprobar";
            }else if(this.approved == 2){
                term = "devolver";
            }else{
                term = "rechazar";
            }
            if(this.approved == 3){
                axios.post(url, {
                    autorizers: autorizers,
                    quota_lvl: doc_lvl,
                })
                .then(response => {
                    answer = response.data;
                    console.log(answer);
                    if(answer == "OK"){
                        Swal.fire({
                            title: '¿Esta seguro de '+term+' el documento?',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, '+term,
                            cancelButtonText: 'Cancelar',
                        }).then((result) => {
                            if (result.value) {
                                setTimeout(() => this.$refs.preQuota.submit(), 500);
                            }
                        });
                    }else{
                        Swal.fire(
                            'Alerta',
                            'Debe agregar por lo menos un autorizador de NIVEL ' + doc_lvl,
                            'warning',
                        );

                    }
                })
                .catch(function(error) {
                    this.test = error;
                });
            }else{
                Swal.fire({
                    title: '¿Esta seguro de '+term+' el documento?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, '+term,
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                        setTimeout(() => this.$refs.preQuota.submit(), 500);
                    }
                });
            }

        },

        authQuota:function(e){
            var term;
            if(this.approved > 3 && this.approved <= 6){
                term = "aprobar";
            }else if(this.approved == 2){
                term = "devolver";
            }else{
                term = "rechazar";
            }
            Swal.fire({
                title: '¿Esta seguro de '+term+' la cotizacion?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, '+term,
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {
                    setTimeout(() => this.$refs.authQuota.submit(), 500);
                }
            });
            e.preventDefault();
        },

        authNego:function(e){
            var term;
            if(this.approved >= 3 && this.approved <= 6){
                term = "aprobar"
            }else if(this.approved == 2){
                term = "devolver"
            }else{
                term = "rechazar"
            }
            Swal.fire({
                title: '¿Esta seguro de '+term+' la negociación?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, '+term,
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {
                    setTimeout(() => this.$refs.authNego.submit(), 500);
                }
            })
            e.preventDefault();

        },
    }
});



