<?php

namespace App\Exports;
use App\Models\Checksheet;
use App\Models\Itemcheckgroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Commoninformation;


class SLFrameExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    use Exportable;

    public function collection()
{
    // Get the unique item check values
    $uniqueItemChecks = Itemcheckgroup::distinct()->pluck('ItemCheck');

    $checksheets = Commoninformation::leftjoin('checksheets', 'checksheets.CommonInfoID', '=', 'commoninformations.CommonInfoID')
        ->where('commoninformations.Status', 2)
        ->where('commoninformations.InspectionLevel', 2)
        ->get();

    $result = [];

    foreach ($checksheets as $item) {
        if (!isset($result[$item->CommonInfoID])) {
            $result[$item->CommonInfoID] = [
                'no' => $no = 1,
                'tgl' => $item->TglProd,
                'shift' => $item->Shift,
                'No. Frame' => $item->NoFrame,
            ];
            $no = $no++;
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

                // If PDI Finding and Repair are both 1, set PDICheck to 1
                if ($item->FindingPDI == 1) {
                    $result[$item->CommonInfoID][$check . '_FindingPDI'] = 'X';
                } else {
                    $result[$item->CommonInfoID][$check . '_FindingPDI'] = 0;
                }
                if ($item->RepairPDI == 1) {
                    $result[$item->CommonInfoID][$check . '_RepairPDI'] = 'V';
                } else {
                    $result[$item->CommonInfoID][$check . '_RepairPDI'] = 0;
                }
            } else {
                if (!isset($result[$item->CommonInfoID][$check . '_FindingQG'])) {
                    $result[$item->CommonInfoID][$check . '_FindingQG'] = 0;
                }
                if (!isset($result[$item->CommonInfoID][$check . '_RepairQG'])) {
                    $result[$item->CommonInfoID][$check . '_RepairQG'] = 0;
                }
                if (!isset($result[$item->CommonInfoID][$check . '_FindingPDI'])) {
                    $result[$item->CommonInfoID][$check . '_FindingPDI'] = 0;
                }
                if (!isset($result[$item->CommonInfoID][$check . '_RepairPDI'])) {
                    $result[$item->CommonInfoID][$check . '_RepairPDI'] = 0;
                }
            }
        }
    }
    // Convert the result array to a collection
    return collect($result);
}

    public function headings(): array
    {
        return [
            ['NO.','Tgl','Shift','Serie','C/Mbr No.1 + Complete Bracket','','','','Hook Frt / CKD','','','','Bracket Tie Down / SLJ-77','','','','Bracket  / SGC -22','','','','Bracket Horn / SLJ-55','','','','Bracket Mtg Cabin A/SLJ-85','','','','C/Mbr No.1,5 + Complete Bracket','','','','Bracket Strut Bar  / SLJ-38','','','','Bracket Radiator  / SLJ-82-83','','','','Reinforcement / SLJ-44 & SLJ-33','','','','Bracket Roller / SLJ-73','','','','Bracket / SLJ-103','','','','C/Mbr No.2 + Complete Bracket','','','','Long Sill   / SLJ-81','','','','Bracket Assy Cab Mtg / SLJ-119','','','','Bracket Eng. Sup.  / SLJ-141','','','','Bracket Cable A / SLJ-65','','','','Bracket Hose / SLJ-35','','','','Hanger Spring / CKD','','','','Bracket Fuel Tank  / SLJ-97','','','','Brkt Brake Hose','','','','C/Mbr No. 3 + Complete Brkt','','','','C/Mbr No.4 + Complete Brkt','','','','Hook Rear / CKD','','','','Brkt Shackle','','','','Brkt Stay muffler / SLJ-97','','','','Brkt Stoper Bumper / SLJ-43','','','','Brkt Mtg SLJ-18 Assy Nut','','','','Brkt Harness , RH side x 3','','','','Bracket / SGJ-22','','','','Bracket Clip Bintang x 2','','','','Bracket New x 3','','','','Cat Belang','','','','Cat Bubble','','','','Vin Number','','','','Grease Shift Lev.','','','','Grease Pin Dumper','','','',],
            ['', '', '', '','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI','','QG','','PDI',''],
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        $style = $sheet->getStyle('A1:EV1');

        $alignment = $style->getAlignment();
        $alignment->setVertical(Alignment::VERTICAL_CENTER);
        $alignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->getStyle("E1:EV1")->getAlignment()->setTextRotation(90);
        $sheet->getDefaultColumnDimension()->setWidth(3); // Set the width of all columns to 20

        $startRow = 1;
        $endRow = 1;

        for ($rowNumber = $startRow; $rowNumber <= $endRow; $rowNumber++) {
            $rowDimension = $sheet->getRowDimension($rowNumber);
            $rowDimension->setRowHeight(100); // Set the height of each row to 50
        }

        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('C1:C2');
        $sheet->mergeCells('D1:D2');
        //HEADER ITEM CHECK
        $sheet->mergeCells('E1:H1');
        $sheet->mergeCells('I1:L1');
        $sheet->mergeCells('M1:P1');
        $sheet->mergeCells('Q1:T1');
        $sheet->mergeCells('U1:X1');
        $sheet->mergeCells('Y1:AB1');
        $sheet->mergeCells('AC1:AF1');
        $sheet->mergeCells('AG1:AJ1');
        $sheet->mergeCells('AK1:AN1');
        $sheet->mergeCells('AO1:AR1');
        $sheet->mergeCells('AS1:AV1');
        $sheet->mergeCells('AW1:AZ1');
        $sheet->mergeCells('BA1:BD1');
        $sheet->mergeCells('BE1:BH1');
        $sheet->mergeCells('BI1:BL1');
        $sheet->mergeCells('BM1:BP1');
        $sheet->mergeCells('BQ1:BT1');
        $sheet->mergeCells('BU1:BX1');
        $sheet->mergeCells('BY1:CB1');
        $sheet->mergeCells('CC1:CF1');
        $sheet->mergeCells('CG1:CJ1');
        $sheet->mergeCells('CK1:CN1');
        $sheet->mergeCells('CO1:CR1');
        $sheet->mergeCells('CS1:CV1');
        $sheet->mergeCells('CW1:CZ1');
        $sheet->mergeCells('DA1:DD1');
        $sheet->mergeCells('DE1:DH1');
        $sheet->mergeCells('DI1:DL1');
        $sheet->mergeCells('DM1:DP1');
        $sheet->mergeCells('DQ1:DT1');
        $sheet->mergeCells('DU1:DX1');
        $sheet->mergeCells('DY1:EB1');
        $sheet->mergeCells('EC1:EF1');
        $sheet->mergeCells('EG1:EJ1');
        $sheet->mergeCells('EK1:EN1');
        $sheet->mergeCells('EO1:ER1');
        $sheet->mergeCells('ES1:EV1');
        //PDI QG
        $sheet->mergeCells('E2:F2');
        $sheet->mergeCells('G2:H2');
        $sheet->mergeCells('I2:J2');
        $sheet->mergeCells('K2:L2');
        $sheet->mergeCells('M2:N2');
        $sheet->mergeCells('O2:P2');
        $sheet->mergeCells('Q2:R2');
        $sheet->mergeCells('S2:T2');
        $sheet->mergeCells('U2:V2');
        $sheet->mergeCells('W2:X2');
        $sheet->mergeCells('Y2:Z2');
        $sheet->mergeCells('AA2:AB2');
        $sheet->mergeCells('AC2:AD2');
        $sheet->mergeCells('AE2:AF2');
        $sheet->mergeCells('AG2:AH2');
        $sheet->mergeCells('AI2:AJ2');
        $sheet->mergeCells('AK2:AL2');
        $sheet->mergeCells('AM2:AN2');
        $sheet->mergeCells('AO2:AP2');
        $sheet->mergeCells('AQ2:AR2');
        $sheet->mergeCells('AS2:AT2');
        $sheet->mergeCells('AU2:AV2');
        $sheet->mergeCells('AW2:AX2');
        $sheet->mergeCells('AY2:AZ2');
        
        $sheet->mergeCells('BA2:BB2');
        $sheet->mergeCells('BC2:BD2');
        $sheet->mergeCells('BE2:BF2');
        $sheet->mergeCells('BG2:BH2');
        $sheet->mergeCells('BI2:BJ2');
        $sheet->mergeCells('BK2:BL2');
        $sheet->mergeCells('BM2:BN2');
        $sheet->mergeCells('BO2:BP2');
        $sheet->mergeCells('BQ2:BR2');
        $sheet->mergeCells('BS2:BT2');
        $sheet->mergeCells('BU2:BV2');
        $sheet->mergeCells('BW2:BX2');
        $sheet->mergeCells('BY2:BZ2');

        $sheet->mergeCells('CA2:CB2');
        $sheet->mergeCells('CC2:CD2');
        $sheet->mergeCells('CE2:CF2');
        $sheet->mergeCells('CG2:CH2');
        $sheet->mergeCells('CI2:CJ2');
        $sheet->mergeCells('CK2:CL2');
        $sheet->mergeCells('CM2:CN2');
        $sheet->mergeCells('CO2:CP2');
        $sheet->mergeCells('CQ2:CR2');
        $sheet->mergeCells('CS2:CT2');
        $sheet->mergeCells('CU2:CV2');
        $sheet->mergeCells('CW2:CX2');
        $sheet->mergeCells('CY2:CZ2');

        $sheet->mergeCells('DA2:DB2');
        $sheet->mergeCells('DC2:DD2');
        $sheet->mergeCells('DE2:DF2');
        $sheet->mergeCells('DG2:DH2');
        $sheet->mergeCells('DI2:DJ2');
        $sheet->mergeCells('DK2:DL2');
        $sheet->mergeCells('DM2:DN2');
        $sheet->mergeCells('DO2:DP2');
        $sheet->mergeCells('DQ2:DR2');
        $sheet->mergeCells('DS2:DT2');
        $sheet->mergeCells('DU2:DV2');
        $sheet->mergeCells('DW2:DX2');
        $sheet->mergeCells('DY2:DZ2');

        $sheet->mergeCells('EA2:EB2');
        $sheet->mergeCells('EC2:ED2');
        $sheet->mergeCells('EE2:EF2');
        $sheet->mergeCells('EG2:EH2');
        $sheet->mergeCells('EI2:EJ2');
        $sheet->mergeCells('EK2:EL2');
        $sheet->mergeCells('EM2:EN2');
        $sheet->mergeCells('EO2:EP2');
        $sheet->mergeCells('EQ2:ER2');
        $sheet->mergeCells('ES2:ET2');
        $sheet->mergeCells('EU2:EV2');

        $alignment = [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ];
    
        $sheet->getStyle('F1:AP1')->getAlignment()->applyFromArray($alignment);
    }
}



