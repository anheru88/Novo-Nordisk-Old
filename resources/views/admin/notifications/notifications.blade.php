@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    @include('admin.layouts.breadcrumbs')
    <div class="quotation" id="app">
        {{-- Notifications --}}
        <section class="content">
            <div class="box box-info">
                <!-- /.box-header -->
                <div class="box-body">
                    @foreach ($postNotifications as $notification)
                        @if ($loop->last)
                        {!! Form::open(['action' => ['NotificationsController@markAsRead', $notification->id],
                        'method' => 'PUT']) !!}
                        <input type="hidden" name="readed" value="1">
                        <button type="submit" class="mark-as-read btn btn-info btn-xs">Marcar todo como
                            leído</button>
                        {!! Form::close() !!}
                        @endif
                    @endforeach
                    <div class="table-responsive">
                        <table id="notifications" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    {{-- <td class="no-sort">
                                    <label class="checkbox-container no-margin">
                                        {{ Form::checkbox('quotation_prod','', false,['id' => 'checkAll']) }}
                                    <span class="checkmark"></span>
                                    </label>
                                    </td> --}}
                                    <th>Asunto</th>
                                    <th>Descripción</th>
                                    <th>Creada el</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(sizeof($postNotifications) > 0 && auth()->user())
                                @forelse ($postNotifications as $notification)
                                <tr>
                                    <td>
                                        {!! Form::open(['action' => ['NotificationsController@notificationView'], 'method' => 'POST']) !!}
                                            <input type="hidden" name="id" value="{{ $notification->id }}" />
                                            <input type="hidden" name="destiny_id"
                                                value="{{ $notification->destiny_id }}" />
                                            <input type="hidden" name="url" value="{{ $notification->url }}" />
                                            <input type="hidden" name="readed" value="1" />
                                            <button type="submit" class="btn btn-link">{{ $notification['type'] }}</button>
                                        {!! Form::close() !!}
                                    </td>
                                    <td>{{ $notification['data'] }}</td>
                                    @php
                                    setlocale(LC_ALL,"es_ES");
                                    // $format = '%B %e, %Y, %l:%M a';
                                    $format = 'm/j/Y, g:i a';
                                    $data = strtotime($notification['created_at']);
                                    $date = date($format , $data);
                                    @endphp
                                    <td>{{ $date }}</td>
                                    <td>
                                        {!! Form::open(['action' => ['NotificationsController@update',
                                        $notification->id], 'method' => 'PUT']) !!}
                                        <input type="hidden" name="id" value="{{ $notification->id }}">
                                        <input type="hidden" name="readed" value="1">
                                        <button type="submit" class="mark-as-read btn btn-warning btn-xs"
                                            data-id="{{ $notification->id }}">Marcar como leído</button>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td>No hay notificaciones sin leer</td>
                                </tr>
                                @endforelse
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </section>
    </div>
</div>
<!-- /.content -->
@endsection

@section('pagescript')
<script>
        var table = $('#notifications').DataTable( {
            'language': {
                "url":  "{{ asset('lang/es/datatable.es.lang') }}",
            },
        });
</script>
@endsection
