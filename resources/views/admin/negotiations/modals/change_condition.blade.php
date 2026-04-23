   <!-- /.Modal de de escala-->

    <div class="modal fade" id="modal-escala-edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">
                        <i class="ion ion-ios-search-strong"></i> Cambiar escala del producto GLUCAGEN® VIAL
                    </h3>
                </div>
                <div class="modal-body">
                    <div>
                        <table class="table no-margin table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Producto</th>
                                    <th>Escala actual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><a href="#">@{{ client_name }}</a></td>
                                    <td><a href="#">@{{ prod_name_modal }}</a></td>
                                    <td>@{{ scale_name_modal }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group"><strong> Seleccione la nueva escala </strong></div>
                    <div class="form-group" v-if="noScalesMsg">
                        El producto no tiene escalas asignadas, @can('products.edit')
                            <a href="{{ route('escalas.index') }}">asigne una aquí</a>
                        @endcan
                    </div>
                    <div class="form-group" v-else>
                        <select id="id_scale" name="id_scale" class="form-control focus filter-table-textarea"
                            v-model="selectScale" required>
                            <option :value="undefined"> - Seleccione - </option>
                            <option v-for="scale in scales" :value="scale.id_scale">@{{ scale.scale_number }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-novo pull-right" v-on:click="updateScale"
                        data-dismiss="modal" aria-label="Close">Cambiar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
