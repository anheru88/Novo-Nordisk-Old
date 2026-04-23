new Vue({
    el: "#app",
    created: function() {
       // alert("asd")
    },
    data: {
        // Scales data
        idArp: "",
        nameService: "",
        arpData: [],
        pbc:"",
    },
    methods: {
        // text Toact message
        mensaje: function() {
            toastr.options = {
                closeButton: false,
                progressBar: true,
                newestOnTop: false
            };
            toastr.success("Escala creada exitosamente").css("width", "auto");
        },


        // Call the scale data to edit.
        editArp: function(id, nameService) {
            var urlProduct = "../../getServiceArp";
            this.idArp = id;
            this.nameService = nameService;
            if (this.idService != "") {
                axios.post(urlProduct, {
                        id: this.idArp
                    })
                    .then(response => {
                        this.arpData = response.data;
                        console.log(this.arpData);
                    })
                    .catch(function(error) {
                        this.test = error;
                    });
            }
        },

        addPbc: function(id,nameService)
        {
            this.idArp          = id;
            this.nameService    = nameService;
        },

        editPbc: function(id, nameService, pbc)
        {
            this.idArp = id;
            this.nameService = nameService;
            this.pbc = pbc;

            var urlProduct = "../../getPbcArp";
            this.idArp = id;
            this.nameService = nameService;
            if (this.idArp != "") {
                axios.post(urlProduct, {
                        id: this.idArp
                    })
                    .then(response => {
                        this.arpData = response.data;
                        console.log(this.arpData);
                    })
                    .catch(function(error) {
                        this.test = error;
                    });
            }
        }
    }
});
