<!-- Modal Para compartir documentos-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">  <i class="fas fa-share-alt"></i> Compartir documentos </h2>
            </div>
            {!! Form::open(['route' => 'sharedgenericdocs', 'method' => 'POST']) !!}
            <div class="modal-body">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="destinatario" class="col-form-label" name="destinatario">Destinatario:</label>
                        {!! Form::text('destinatario', null, ['class'=>'form-control text-primary', 'id'=>'destinatario', 'placeholder'=>'usuario@email.com']) !!}
                    </div>
                    <div class="form-group">
                        <label for="mensaje" class="col-form-label" name="mensaje">Mensaje:</label>
                        {!! Form::textarea('mensaje', null, ['class'=>'form-control','id'=>'message-text','placeholder'=>'Adjunto los siguientes documentos', 'rows' => '6']) !!}
                    </div>
                </div>
                <div class="col-xs-12 ">
                    <div class="quot-data-box">
                        <div class="quot-data-box-content">
                            <label for="timelast" class="col-form-label">Tiempo de expiración del link</label>
                            <select class="Clientes-select form-control focus filter-table-textarea" id="timelast" name="timelast"
                                aria-label="Default select example">
                                <option selected>Seleccione</option>
                                <option value="10min">10 min</option>
                                <option value="30min">30 min</option>
                                <option value="60min">1 hora</option>
                                <option value="3hr">3 horas</option>
                                <option value="8hr">8 horas</option>
                                <option value="14hr">14 horas</option>
                                <option value="1d">1 día</option>
                                <option value="2d">2 día</option>
                                <option value="5d">5 días</option>
                                <option value="1sem">1 semana</option>
                                <option value="2sem">2 semanas</option>
                                <option value="1mes">1 mes</option>
                                <option value="2meses">2 meses</option>
                                <option value="infinito">Sin límite de caducidad</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="gray-box-modal">
                        <div class="gray-box-modal-title">Documentos:</div>
                        <div class="form-group">
                            <div class="docs-modal animated" v-for="(input, index) in sharedDocs">
                                <div class="doc_name">@{{ input.doc_name }}</div>
                                <div class="remove-doc">
                                    <a class="btn btn-xs btn-danger" v-on:click="removeSharedFiles(input.id_doc)">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                {!! Form::submit('COMPARTIR', ['class' => 'btn  btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- /.box-header -->
