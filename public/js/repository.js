new Vue({
    el: '#app',
    created: function(){
       // this.addtoShare();
    },
    data: {
        levelAuth:false,
        levelCheck:"",
        idFolder:"",
        folderName:"",
        folderNameOld:"",
        sharedDocs:[],
    },
    methods:{
        showLevel:function(){
            alert("asdsa");
        },

        editFolder:function(idFolder, folderName){

            this.idFolder       = idFolder;
            this.folderName     = folderName;
            this.folderNameOld  = folderName;

        },
        addtoShare:function(idFile){
            var urlQuery = '../addSharedFiles';
            axios.post(urlQuery,{
                idFile: idFile,
                }).then(response => {
                    console.log(response.data);
                    if (response.data == "ok") {
                        toastr.options = {
                            "closeButton": false,
                            "progressBar": true,
                            "newestOnTop": false
                        };
                        toastr.success('Se agrego el archivo al listado para compartir').css("width", "auto")
                    }else{
                        toastr.options = {
                            "closeButton": false,
                            "progressBar": true,
                            "newestOnTop": false
                        };
                        toastr.error('El archivo ya fue agregado al listado').css("width", "auto")
                    }
                this.errors = [];
            })
            .catch(function (error) {
                this.test = error;
            })
        },
        showSharedFiles:function(){
            var urlQuery = '../getSharedFiles';
            axios.post(urlQuery,{
                }).then(response => {
                    console.log(response.data);
                    this.sharedDocs = response.data;
                    this.errors = [];
                })
            .catch(function (error) {
                this.test = error;
            })
        },
        removeSharedFiles($idFile){
            var urlQuery = '../removeSharedFiles';
            axios.post(urlQuery,{
                idFile: $idFile
                }).then(response => {
                    console.log(response.data);
                    this.sharedDocs = response.data;
                    this.errors = [];
                })
            .catch(function (error) {
                this.test = error;
            })
        }
    }
});



