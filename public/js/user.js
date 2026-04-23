new Vue({
    el: "#users",
    created: function() {
        this.showLevel();
        this.changeAuthAlltime();
    },
    data: {
        levelAuth: false,
        levelCheck: false,
        changeImage: false,
    },
    methods: {
        showLevel: function() {
            if (document.getElementById("authorizer")) {
                this.levelCheck = document.getElementById("authorizer").value;
                console.log(this.levelCheck);
                if (this.levelCheck = 1) {
                    this.levelAuth = true;
                    // alert(this.levelCheck);
                }
            }
        },
        testAuth0: function() {

            var AUTH0_DOMAIN = "novonordiskco.auth0.com";
            var AUTH0_KEY = "n1cSgRaHn4VizfYvgyXy1pcWQuYByOHt";
            var AUTH0_SECRET = "wGP4878TlmsA6p0ZY7iA-Zkadph8vxM3t_BeI2zvd_TNcLP8z2cL4a9GV60GlKPW";

            //var AUDIENCE_URL = "https://" + AUTH0_DOMAIN + "/api/";
            var AUDIENCE_URL = 'https://comercial.nnco.cloud/api/';
            var OAUTH_TOKEN_URL = "https://" + AUTH0_DOMAIN + "/oauth/token";

            var token = "";

            axios.post(OAUTH_TOKEN_URL, {
                    async: true,
                    crossDomain: true,
                    audience: AUDIENCE_URL,
                    grant_type: "client_credentials",
                    client_id: AUTH0_KEY,
                    client_secret: AUTH0_SECRET,
                    /* username : 'pablo.leyton@gmail.com',
                    password : '123456',
                    scope   : 'openid profile email',*/
                }, {
                    headers: {
                        'content-type': 'application/json'
                    }
                }).then(response => {
                    console.log(response.data);
                    token = response.data['access_token'];
                    console.log(token);
                    var urlProduct = 'https://comercial.nnco.cloud/create-user';
                    axios.post(urlProduct, {
                            async: true,
                            crossDomain: true,
                        }, {
                            headers: {
                                Authorization: token,
                            }
                        }).then(response => {
                            console.log(response.data);
                            this.errors = [];
                            //console.log(this.allProducts);
                        })
                        .catch(function(error) {
                            this.test = error;
                        });
                })
                .catch(function(error) {
                    this.test = error;
                });
        },
        changeAuth: function(id) {
            var id = '#rol' + id;
            if ($(id).is(':checked')) {
                this.levelAuth = true;
            } else {
                this.levelAuth = false;
            }
        },
        changeAuthAlltime: function() {
            var id = '#rol' + 2;
            if ($(id).is(':checked')) {
                this.levelAuth = true;
            } else {
                this.levelAuth = false;
            }
        }
    }
});