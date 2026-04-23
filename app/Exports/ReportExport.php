<?php

namespace App\Exports;

use App\QuotationDetails;
use App\Client;
use App\NegotiationDetails;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ReportExport implements FromView, WithEvents, ShouldAutoSize, WithChunkReading, ShouldQueue
{
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:S1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('c7c7c7');
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal('center');
            }
        ];
    }

    public function __construct($oldReq)
    {
        $this->oldReq = $oldReq;
    }

    public function view(): View
    {
        // dd($this->oldReq['user']);
        if (isset($this->oldReq['client'], $this->oldReq['payterm'])) {
            $type           = $this->oldReq['type'];
            $idClient       = $this->oldReq['client'];
            $idProduct      = $this->oldReq['product'];
            $idPayterm      = $this->oldReq['payterm'];
            $idChannel      = $this->oldReq['channel'];
            $idUser         = $this->oldReq['user'];
            $brand          = $this->oldReq['brand'];
            $dateIni        = $this->oldReq['desde'];
            $dateEnd        = $this->oldReq['hasta'];
            $excel          = $this->oldReq['excel'];
        } else {
            $type           = $this->oldReq['type'];
            // $idClient       = $this->oldReq['client'];
            $idProduct      = $this->oldReq['product'];
            // $idPayterm      = $this->oldReq['payterm'];
            $idChannel      = $this->oldReq['channel'];
            $idUser         = $this->oldReq['user'];
            // $brand          = $this->oldReq['brand'];
            $dateIni        = $this->oldReq['desde'];
            $dateEnd        = $this->oldReq['hasta'];
            $excel          = $this->oldReq['excel'];
        }
        if ($type == 'cot') {
            $results = QuotationDetails::orderBy('id_quota_det', 'ASC')/*join('nvn_products', 'nvn_products.id_product', '=', 'nvn_quotations_details.id_product') // se incluyo el join de la tabla para ordenar por el nombre del producto
                ->orderBy('nvn_products.prod_name')
                ->select('nvn_quotations_details.*')*/;

            if ($idProduct != "") {
                $results = $results->where('id_product', $idProduct);
            }
            if (isset($idPayterm)) {
                if ($idPayterm != "") {
                    $results = $results->where('id_payterm', $idPayterm);
                }
            }
            if ($dateIni != "" || $dateEnd != "" || $idChannel != "" || $idUser != "") {
                $results = $results->whereHas('quotation', function ($query) use ($dateIni, $dateEnd, $idUser, $idChannel) {
                    if ($dateIni != "") {
                        $q = $query->where('quota_date_ini', '<=', $dateIni);
                    }
                    if ($dateEnd != "") {
                        $q = $query->where('quota_date_end', '>=', $dateEnd);
                    }
                    if ($idChannel != "") {
                        $q = $query->where('id_channel', $idChannel);
                    }
                    if ($idUser != "") {
                        $q = $query->where('created_by', $idUser); // filtro de fecha inicial y cierre de negociacion
                    }
                    return $q;
                });
            }
            $results = $results->where('is_valid', 1);
            $results = $results->with('payterm', 'quotation', 'client');
            $results = $results->get();
            $results = $results->chunk(1000);
            return view('admin.reports.reportsexcel', [
                'results' => $results,
            ]);
        } elseif ($type == 'neg') {
            $results = NegotiationDetails::orderBy('id_negotiation_det', 'ASC');
            if ($idProduct != "") {
                $results = $results->where('id_product', $idProduct);
            }
            if ($dateIni != "" || $dateEnd != "" || $idChannel != "" || $idUser != "") {
                $results = $results->whereHas('quotation', function ($query) use ($dateIni, $dateEnd, $idUser, $idChannel) {
                    if ($dateIni != "") {
                        $q = $query->where('quota_date_ini', '<=', $dateIni);
                    }
                    if ($dateEnd != "") {
                        $q = $query->where('quota_date_end', '>=', $dateEnd);
                    }
                    if ($idChannel != "") {
                        $q = $query->where('id_channel', $idChannel);
                    }
                    if ($idUser != "") {
                        $q = $query->where('created_by', $idUser); // filtro de fecha inicial y cierre de negociacion
                    }
                    return $q;
                });
            }
            $results = $results->where('is_valid', 1);
            $results = $results->with('payterm', 'negotiation', 'quotation', 'client', 'concept', 'product');
            $results = $results->get();
            // $results = $results->take(3000);
            // $results = $results->chunk(1000);
            // dd($results);
            return view('admin.reports.reportsexcelneg', [
                'results' => $results,
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
