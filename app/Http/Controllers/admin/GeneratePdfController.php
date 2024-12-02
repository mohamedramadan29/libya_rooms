<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Companies;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class GeneratePdfController extends Controller
{
    public function generatecompanypdf()
    {
        // جلب بيانات الشركات مع العلاقات المطلوبة
        $companies = Companies::with('subcategory', 'categorydata', 'companytype')->orderby('id', 'desc')->get();

        // إعداد محتوى HTML
        $html = '
        <html lang="ar" dir="rtl">
        <head>
            <style>
                body {
                    font-family: "Cairo", sans-serif; /* اختر خط يدعم اللغة العربية */
                    text-align: right; /* محاذاة النصوص لليمين */
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #000;
                    padding: 8px;
                    text-align: right; /* لمحاذاة النصوص داخل الجدول */
                }
                th {
                    background-color: #f2f2f2; /* لون خلفية للرأس */
                }
            </style>
        </head>
        <body>
            <h1>تقرير الشعب</h1>

            <table>
                <thead>
                    <tr>
                        <th>رقم القيد</th>
                        <th>اسم النشاط</th>
                        <th>الشعبة</th>
                        <th>الشكل القانوني</th>
                        <th>تاريخ الانتهاء</th>
                        <th>نوع النشاط</th>
                    </tr>
                </thead>
                <tbody>';

        // تعبئة البيانات داخل الجدول
        foreach ($companies as $company) {
            // التحقق من تاريخ الانتهاء
            $expiryDate = 'لم يحدد بعد';
            if ($company->first_market_confirm_date || $company->new_market_confirm_date) {
                $expiryDate = $company->expiry_date ? $company->expiry_date->format('Y-m-d') : 'لم يحدد بعد';
            }

            $html .= '
                    <tr>
                        <td>' . $company->company_number . '</td>
                        <td>' . $company->trade_name . '</td>
                        <td>' . ($company->categorydata ? $company->categorydata->name : 'غير محدد') . '</td>
                        <td>' . ($company->companytype ? $company->companytype->name : 'غير محدد') . '</td>
                        <td>' . $expiryDate . '</td>
                        <td>' . ($company->subcategory ? $company->subcategory->name : 'غير محدد') . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        // إعداد mPDF
        $mpdf = new Mpdf([
            'default_font' => 'Cairo', // خط يدعم اللغة العربية
        ]);

        // تحميل المحتوى إلى ملف PDF
        $mpdf->WriteHTML($html);

        // توليد ملف PDF وإرساله للتنزيل
        return $mpdf->Output('تقرير عن الشعب.pdf', 'I'); // 'I' لعرض الملف في المتصفح
    }


}
