<?php

namespace App\Traits;

use App\CreditNotesClients;
use App\CreditNotesClientsBills;
use App\CreditNotesDetails;
use App\CreditNotesDetailsBills;
use App\Events\OrderNotificationsEvent;
use App\QuotationApprovers;
use App\QuotationxStatus;
use Illuminate\Support\Facades\URL;
use App\Notifications;
use App\QuotationxComments;
use Caffeinated\Shinobi\Models\Role;

trait GenericTrait
{


    public function storeQuotationStatus($quota, $status, $id, $statusName, $comments)
    {
        $url = URL::to("/");
        $users_notified = Role::with('users')->where('slug', 'admin_venta')->get();
        //$users_notified = User::where('nickname', 'CMNM')->get();
        $users = [];

        foreach ($users_notified as $user) {
            // Add the first preautorizer
            QuotationApprovers::create([
                'answer'        => 0,
                'quotation_id'  => $quota->id_quotation,
                'user_id'       => $user->users[0]->id
            ]);
            // Save the Quotation Status to get a follow-up
            QuotationxStatus::create([
                'status_id'     => $status,
                'user_id'       => Auth()->user()->id,
                'quotation_id'  => $quota->id_quotation,
            ]);

            array_push($users, $user->users[0]->id);
        }

        // Add comments to records
        QuotationxComments::create([
            'id_quotation'  => $id,
            'created_by'    => Auth()->user()->id,
            'type_comment'  => $statusName,
            'text_comment'  => $comments,
        ]);

        // Send Notification
        $type = "Creación de cotización";
        if($status == 6){
            $msg = "La cotización $quota->quota_consecutive fue creada satisfactoriamente.";
            $url = $url . '/cotizaciones/' . $quota->id_quotation;
        }else{
            $msg = 'Se espera la aprobación de la cotización ' . $quota->quota_consecutive;
            $url = $url . '/preautorizarcotizacion/' . $quota->id_quotation;
        }

        Notifications::sendNotification($msg, $users, $url, $type);

    }

    public function updateQuotationStatus($quota, $status, $users, $id, $statusName, $comments)
    {
        $url = URL::to("/");
         //Change autorizer aprobation
        QuotationApprovers::where('quotation_id', $id)
        ->where('user_id', Auth()->user()->id)
        ->update(['answer' => $status]);
        // if has users add to the DB
        if (sizeof($users) > 0) {
            foreach ($users as $key => $user) {
                $saveApprovers = new QuotationApprovers();
                $saveApprovers->quotation_id    = $id;
                $saveApprovers->user_id         = $user;
                $saveApprovers->save();
            }
        }

        // Create new quotation status
        QuotationxStatus::create([
            'status_id'     => $status,
            'user_id'       => Auth()->user()->id,
            'quotation_id'  => $id,
        ]);

        // Add comments to records
        QuotationxComments::create([
            'id_quotation'  => $id,
            'created_by'    => Auth()->user()->id,
            'type_comment'  => $statusName,
            'text_comment'  => $comments,
        ]);

        // Add creator to notificate.
        array_push($users, $quota->created_by);

        $msg = 'La cotización ' . $quota->quota_consecutive . ' se encuentra en estado ' . $statusName;

        if($status == 1){
            $msg = 'Se espera la aprobación de la cotización ' . $quota->quota_consecutive;
        }

        // Send Notification
        $type = "Actualización de cotización";

        $url = $url.'/cotizaciones/' . $id;

        Notifications::sendNotification($msg, $users, $url, $type);

    }


    public function getPublishedAtFormattedAttribute($value)
    {
        return $this->published_at->format('d.m.Y');
    }

    public function createNoteClientBill($row, $id)
    {
        $clientB = CreditNotesClientsBills::create([
            'client_sap_code'   => $row['codigo_cliente'],
            'concept'           => $row['concepto'],
            'bill'              => $row['factura'],
            'id_credit_notes'   => $id,
        ]);

        return $clientB;
    }

    public function createNotesDetailsBill($row,$id_client, $tab)
    {
        $clientDetailsBill = CreditNotesDetailsBills::create([
            'id_credit_notes_clients_b' => $id_client,
            'prod_sap_code'             => $row['material'],
            'real_qty'                  => ceil(intval($row['real_qty'])),
            'nc_value'                  => floatval($row['valor_nc']),
            'nc_individual'             => floatval($row['nc_individual']),
            'tab_xls'                   => $tab,
            'concept'                   => $row['concepto'],
        ]);

        return $clientDetailsBill;
    }


    public function createNoteClient($row, $id)
    {
        $client = CreditNotesClients::create([
            'client_sap_code'   => $row['codigo_cliente'],
            'concept'           => $row['concepto'],
            'bill'              => $row['factura'],
            'id_credit_notes'   => $id,
        ]);

        return $client;
    }

    public function createNotesDetails($row,$id_client, $tab)
    {
        $clientDetails = CreditNotesDetails::create([
            'id_credit_notes_clients'   => $id_client,
            'prod_sap_code'             => $row['material'],
            'real_qty'                  => ceil(floatval($row['real_qty'])),
            'nc_value'                  => floatval($row['valor_nc']),
            'nc_individual'             => floatval($row['nc_individual']),
            'tab_xls'                   => $tab,
            'concept'                   => $row['concepto'],
        ]);

        return $clientDetails;
    }
}
