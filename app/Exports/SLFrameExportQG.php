<?php

namespace App\Exports;
use App\Models\Checksheet;
use App\Models\Itemcheckgroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Commoninformation;

use Maatwebsite\Excel\Concerns\FromCollection;

class SLFrameExportQG implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    use Exportable;

    private $startDate;
    private $endDate;
    private $searchBy;

    // Constructor to accept the start and end dates
      // Constructor to accept the start and end dates and searchBy option
      public function __construct($startDate, $endDate, $searchBy) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->searchBy = $searchBy;
    }

    public function collection()
    {
        // Get the unique item check values
        $uniqueItemChecks = Itemcheckgroup::distinct()->pluck('ItemCheck');

        $checksheetsQuery = Commoninformation::rightJoin('checksheets', 'checksheets.CommonInfoID', '=', 'commoninformations.CommonInfoID')
            ->where('commoninformations.Status', 2)
            ->where('commoninformations.InspectionLevel', 2);

        // Check if start and end dates are set
        if (isset($this->startDate) && isset($this->endDate)) {
            // Use the appropriate date column based on the searchBy option
            if ($this->searchBy === 'dateRangeCreatedAt') {
                $checksheetsQuery->whereBetween('commoninformations.created_at', [$this->startDate, $this->endDate]);
            } elseif ($this->searchBy === 'dateRangeProductionDate') {
                $checksheetsQuery->whereBetween('commoninformations.TglProd', [$this->startDate, $this->endDate]);
            }
        }

        $checksheets = $checksheetsQuery->get();
        $result = [];
        foreach ($checksheets as $item) {
            if (!isset($result[$item->CommonInfoID])) {
                $result[$item->CommonInfoID] = [
                    'tgl' => $item->TglProd,
                    'shift' => $item->Shift,
                    'No. Frame' => $item->NoFrame,
                    'Status' =>  $item['QualityStatus'],
                ];


            }

        foreach ($uniqueItemChecks as $check) {
            $checkName = $item->ItemCheck;

            if ($check == $checkName) {
                // If QG Finding and Repair are both 1, set QGCheck to 1
                if ($item->FindingQG == 1) {
                    $result[$item->CommonInfoID][$check . '_FindingQG'] = 'X';
                } else {
                    $result[$item->CommonInfoID][$check . '_FindingQG'] = 0;
                }

                if ($item->RepairQG == 1) {
                    $result[$item->CommonInfoID][$check . '_RepairQG'] = 'V';
                } else {
                    $result[$item->CommonInfoID][$check . '_RepairQG'] = 0;
                }

            } else {
                if (!isset($result[$item->CommonInfoID][$check . '_FindingQG'])) {
                    $result[$item->CommonInfoID][$check . '_FindingQG'] = 0;
                }

                if (!isset($result[$item->CommonInfoID][$check . '_RepairQG'])) {
                    $result[$item->CommonInfoID][$check . '_RepairQG'] = 0;
                }
            }
        }
            // Remove the unwanted keys
            unset(
                $result[$item->CommonInfoID]['_FindingQG'],
                $result[$item->CommonInfoID]['_RepairQG'],
            );
        }

        // Add missing No. Frame entries
        $compare = Commoninformation::where('commoninformations.Status', 2)
            ->where('commoninformations.InspectionLevel', 2);
            // Check if start and end dates are set
        if (isset($this->startDate) && isset($this->endDate)) {
            $compare->whereBetween('commoninformations.TglProd', [$this->startDate, $this->endDate]);
        }
        $compare = $compare->get()->toArray();


        foreach ($compare as $frame) {
            if (!in_array($frame['NoFrame'], array_column($result, 'No. Frame'))) {
                $result[] = [
                    'tgl' => $frame['TglProd'],
                    'shift' => $frame['Shift'],
                    'No. Frame' => $frame['NoFrame'],
                    'Status' =>  $frame['QualityStatus'],
                ];
            }
        }
        // Sort the result array by No. Frame column in ascending order
        usort($result, function ($a, $b) {
            return strcmp($a['No. Frame'], $b['No. Frame']);
        });


        foreach ($result as &$item) {
            $item['total'] = count($item) > 5 ? 1 : 0;
        }
        if (!empty($result)) {
            $maxCount = max(array_map('count', $result));
        } else {
            $maxCount = 0;
        }

        // Add blank elements to the arrays in $result
        foreach ($result as &$item) {
            $item += array_fill(count($item), $maxCount - count($item), '');
        }

        // Remove any '&' reference symbols
        unset($item);
        // Convert the result array to a collection
        return collect($result);

    }
    public function headings(): array
    {
        return [
            ['NO.','Tgl','Shift','Serie','C/Mbr No.1 + Complete Bracket','','Hook Frt / CKD','','Bracket Tie Down / SLJ-77','','Bracket  / SGC -22','','Bracket Horn / SLJ-55','','Bracket Mtg Cabin A/SLJ-85','','C/Mbr No.1,5 + Complete Bracket','','Bracket Strut Bar  / SLJ-38','','Bracket Radiator  / SLJ-82-83','','Reinforcement / SLJ-44 & SLJ-33','','Bracket Roller / SLJ-73','','Bracket / SLJ-103','','C/Mbr No.2 + Complete Bracket','','Long Sill   / SLJ-81','','Bracket Assy Cab Mtg / SLJ-119','','Bracket Eng. Sup.  / SLJ-141','','Bracket Cable A / SLJ-65','','Bracket Hose / SLJ-35','','Hanger Spring / CKD','','Bracket Fuel Tank  / SLJ-97','','Brkt Brake Hose','','C/Mbr No. 3 + Complete Brkt','','C/Mbr No.4 + Complete Brkt','','Hook Rear / CKD','','Brkt Shackle','','Brkt Stay muffler / SLJ-97','','Brkt Stoper Bumper / SLJ-43','','Brkt Mtg SLJ-18 Assy Nut','','Brkt Harness , RH side x 3','','Bracket / SGJ-22','','Bracket Clip Bintang x 2','','Bracket New x 3','','Cat Belang','','Cat Bubble','','Vin Number','','Grease Shift Lev.','','Grease Pin Dumper','','Total',],
            ['', '', '', '','1.1','1.1','1.2','1.2','1.3','1.3','1.4','1.4','1.5','1.5','1.6','1.6','2.1','2.1','2.2','2.2','2.3','2.3','2.4','2.4','2.5','2.5','2.6','2.6','3.1','3.1','3.2','3.2','3.3','3.3','3.4','3.4','3.5','3.5','3.6','3.6','3.7','3.7','3.8','3.8','4.1','4.1','4.2','4.2','4.3','4.3','4.4','4.4','4.5','4.5','4.6','4.6','4.7','4.7','4.8','4.8','5.1','5.1','5.2','5.2','6.1','6.1','6.2','6.2','7.1','7.1','7.2','7.2','7.3','7.3','7.4','7.4','7.5','7.5'],
            ['', '', '', '','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG','','QG',''],

        ];
    }

    public function styles(Worksheet $sheet)
    {
        $style = $sheet->getStyle('A1:EW1');

        $alignment = $style->getAlignment();
        $alignment->setVertical(Alignment::VERTICAL_CENTER);
        $alignment->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("E1:EW1")->getAlignment()->setTextRotation(90);
        $sheet->getDefaultColumnDimension()->setWidth(3); // Set the width of all columns to 20

        $startRow = 1;
        $endRow = 1;

        for ($rowNumber = $startRow; $rowNumber <= $endRow; $rowNumber++) {
            $rowDimension = $sheet->getRowDimension($rowNumber);
            $rowDimension->setRowHeight(100); // Set the height of each row to 50
        }

        $sheet->mergeCells('A1:A3');
        $sheet->mergeCells('B1:B3');
        $sheet->mergeCells('C1:C3');
        $sheet->mergeCells('D1:D3');
        //HEADER ITEM CHECK
        $sheet->mergeCells('E1:F1');
        $sheet->mergeCells('G1:H1');
        $sheet->mergeCells('I1:J1');
        $sheet->mergeCells('K1:L1');
        $sheet->mergeCells('M1:N1');
        $sheet->mergeCells('O1:P1');
        $sheet->mergeCells('Q1:R1');
        $sheet->mergeCells('S1:T1');
        $sheet->mergeCells('U1:V1');
        $sheet->mergeCells('W1:X1');
        $sheet->mergeCells('Y1:Z1');
        $sheet->mergeCells('AA1:AB1');
        $sheet->mergeCells('AC1:AD1');
        $sheet->mergeCells('AE1:AF1');
        $sheet->mergeCells('AG1:AH1');
        $sheet->mergeCells('AI1:AJ1');
        $sheet->mergeCells('AK1:AL1');
        $sheet->mergeCells('AM1:AN1');
        $sheet->mergeCells('AO1:AP1');
        $sheet->mergeCells('AQ1:AR1');
        $sheet->mergeCells('AS1:AT1');
        $sheet->mergeCells('AU1:AV1');
        $sheet->mergeCells('AW1:AX1');
        $sheet->mergeCells('AY1:AZ1');
        $sheet->mergeCells('BA1:BB1');
        $sheet->mergeCells('BC1:BD1');
        $sheet->mergeCells('BE1:BF1');
        $sheet->mergeCells('BG1:BH1');
        $sheet->mergeCells('BI1:BJ1');
        $sheet->mergeCells('BK1:BL1');
        $sheet->mergeCells('BM1:BN1');
        $sheet->mergeCells('BO1:BP1');
        $sheet->mergeCells('BQ1:BR1');
        $sheet->mergeCells('BS1:BT1');
        $sheet->mergeCells('BU1:BV1');
        $sheet->mergeCells('BW1:BX1');
        $sheet->mergeCells('BY1:BZ1');
        $sheet->mergeCells('CA1:CA3');

        //qg
        //HEADER ITEM CHECK
        $sheet->mergeCells('E3:F3');
        $sheet->mergeCells('G3:H3');
        $sheet->mergeCells('I3:J3');
        $sheet->mergeCells('K3:L3');
        $sheet->mergeCells('M3:N3');
        $sheet->mergeCells('O3:P3');
        $sheet->mergeCells('Q3:R3');
        $sheet->mergeCells('S3:T3');
        $sheet->mergeCells('U3:V3');
        $sheet->mergeCells('W3:X3');
        $sheet->mergeCells('Y3:Z3');
        $sheet->mergeCells('AA3:AB3');
        $sheet->mergeCells('AC3:AD3');
        $sheet->mergeCells('AE3:AF3');
        $sheet->mergeCells('AG3:AH3');
        $sheet->mergeCells('AI3:AJ3');
        $sheet->mergeCells('AK3:AL3');
        $sheet->mergeCells('AM3:AN3');
        $sheet->mergeCells('AO3:AP3');
        $sheet->mergeCells('AQ3:AR3');
        $sheet->mergeCells('AS3:AT3');
        $sheet->mergeCells('AU3:AV3');
        $sheet->mergeCells('AW3:AX3');
        $sheet->mergeCells('AY3:AZ3');
        $sheet->mergeCells('BA3:BB3');
        $sheet->mergeCells('BC3:BD3');
        $sheet->mergeCells('BE3:BF3');
        $sheet->mergeCells('BG3:BH3');
        $sheet->mergeCells('BI3:BJ3');
        $sheet->mergeCells('BK3:BL3');
        $sheet->mergeCells('BM3:BN3');
        $sheet->mergeCells('BO3:BP3');
        $sheet->mergeCells('BQ3:BR3');
        $sheet->mergeCells('BS3:BT3');
        $sheet->mergeCells('BU3:BV3');
        $sheet->mergeCells('BW3:BX3');
        $sheet->mergeCells('BY3:BZ3');


        $alignment = [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ];

        $sheet->getStyle('F1:AP1')->getAlignment()->applyFromArray($alignment);
    }
}
