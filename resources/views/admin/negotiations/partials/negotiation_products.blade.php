<div class="col-xs-12 col-sm-6 no-padding-right">
    <div class="container-fixed left-products" >
        <div class="row quot-add-product">
            <div class="col-xs-12">
                <div class="box-title"><i class="ion ion-compose"></i> Productos agregados a la negociación</div>
            </div>
            <div class="col-xs-12 col-sm-8" >
                <div class="box-subtitle">
                    Productos negociados
                </div>
                <div class="left-col" id="scroll-prods">
                    <input type="checkbox" name="remove-all" id="remove-all"  v-on:click="removeAllProductsAsistida('productsNeg')"> Todos
                    <div v-for="(input, index) in inputProducts" :id="'prod'+index" class="left-product-name animated" :class="{highlight:selected2 == 'prod'+index}"
                        v-on:click="previewProductNego(index)" @click="selected2 = 'prod'+index">
                        <input class="top-margin productsNeg" type="checkbox" name="productsNeg" :id="'productsNeg-'+index" :value="index"  v-on:click="removeSingleProductsAsistida()">
                        <div class="prod-left" v-bind:class="[input.warning >= 2 ? 'prod-disabled' : '']"> @{{ input.productname }} - @{{ input.descuento }}%</div>
                        <div class="button-right">
                            <div class="eyebtn btn btn-xs prod-disabled" v-if="input.warning >= 2">
                                <i class="fas fa-eye-slash"></i>
                            </div>
                            <div class="eyebtn btn btn-xs" v-on:click="showProduct(index,input.prodPosition)" v-else>
                                <i class="fas fa-eye" v-if="input.visible == 'SI'"></i>
                                <i class="fas fa-eye-slash" v-else></i>
                            </div>
                            <a class="btn btn-xs btn-danger alifn-right"
                                v-on:click="removeProduct(index,input.prodPosition)">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                        <div class="warning"  v-if="input.warning >= 1">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 no-padding-left">
                <div class="box-subtitle">
                    Descripcion de producto
                </div>
                <div class="right-col">
                    <div class="right-subtitle">Tipo de descuento</div>
                    <div class="right-description">@{{ n_tipoDescuento }}</div>
                    <div class="right-subtitle">Descuento</div>
                    <div class="right-description">@{{ n_descuento }}%</div>
                    <div class="right-subtitle">Descuento acumulado</div>
                    <div class="right-description">@{{ n_descAcumulado }}%</div>
                    <div class="right-subtitle">Concepto</div>
                    <div class="right-description">@{{ n_conceptotext }}</div>
                    <div class="right-subtitle">Aclaración</div>
                    <div class="right-description">@{{ n_aclaracion }}</div>
                    <div class="right-subtitle">Sujero a volumen</div>
                    <div class="right-description">@{{ n_volumen }}</div>
                    <div class="right-subtitle">Observaciones</div>
                    <div class="right-description">@{{ n_observaciones }}</div>
                    <div class="right-warning" v-if="n_errorNego.length">
                        <div class="right-description" v-for="error in n_errorNego">*@{{ error }}</div>
                    </div>
                </div>

            </div>
            <div class="removeBtn">
                <button class="btn btn-danger btn-sm" v-on:click="removeProductMasive('productsNeg')" type="button">
                    <i class="fas fa-trash"></i> REMOVER PRODUCTOS
                </button>
            </div>
        </div>
    </div>
</div>
