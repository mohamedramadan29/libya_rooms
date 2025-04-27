<?php

namespace App\Exports;


use App\Models\admin\Companies;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CompanyExport implements FromCollection, WithHeadings, WithStyles
{
    use Exportable;

    public function collection()
    {
        $user = Auth::user();
        // جلب البيانات المطلوبة مع العلاقات
        $companies = Companies::with('subcategory', 'categorydata', 'companytype')
        ->where('region', $user->regions)
            ->where('branch', $user->branches)
            ->orderby('id', 'desc')
            ->get();

        // معالجة البيانات المراد تصديرها
        return $companies->map(function ($company) {
            // التحقق من تاريخ الانتهاء
            $expiryDate = 'لم يحدد بعد';
            if ($company->first_market_confirm_date || $company->new_market_confirm_date) {
                $expiryDate = $company->expiry_date ? $company->expiry_date->format('Y-m-d') : 'لم يحدد بعد';
            }

            return [
                $company->company_number,
                $company->trade_name,
                $company->categorydata->name ?? 'غير محدد',
                $company->companytype->name ?? 'غير محدد',
                $expiryDate,
                $company->subcategory->name ?? 'غير محدد',
                $company->name,
                $company->personal_number,
                $company->id_number,
                $company->isdar_date,
                $company->tourism_expire_date,
            ];
        });
    }
    public function headings(): array
    {
        return [
            ' رقم القيد ',
            'اسم النشاط',
            'الشعبة',
            'الشكل القانوني',
            'تاريخ الانتهاء',
            'نوع النشاط',
            ' الممثل القانوني ',
            ' رقم اثبات الهوية ',
            ' الرقم الوطني',
            'تاريخ انتهاء السجل  التجاري ',
            'تاريخ انتهاء إذن السياحة'
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // ضبط التنسيق لدعم اللغة العربية
        $sheet->getStyle('A:Z')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT, // محاذاة إلى اليمين
                'vertical' => Alignment::VERTICAL_CENTER,    // محاذاة عمودية
            ],
            'font' => [
                'name' => 'Arial', // استخدام خط يدعم العربية
                'size' => 12,
            ],
        ]);

        // تعيين اتجاه الورقة إلى RTL
        $sheet->setRightToLeft(true);

        return [];
    }
}
