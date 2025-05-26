<?php

namespace App\Http\Controllers\admin;

use Mpdf\Mpdf;
use Illuminate\Http\Request;
use App\Models\admin\Companies;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GeneratePdfController extends Controller
{
    public function generatecompanypdf()
    {
        $user = Auth::user();
        // جلب بيانات الشركات مع العلاقات المطلوبة
     //   $companies = Companies::with('subcategory', 'categorydata', 'companytype')->orderby('id', 'desc')->get();

     if ($user->type == 'admin') {
        $companies = Companies::with('subcategory', 'categorydata', 'companytype')
        ->orderby('id', 'desc')
        ->get();
    } elseif ($user->type == 'supervisor') {
        $companies = Companies::with('subcategory', 'categorydata', 'companytype')
        ->where('region', $user->regions)
            ->where('branch', $user->branches)
            ->orderby('id', 'desc')
            ->get();
    }
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
                        <th>تاريخ تقديم الطلب</th>
                        <th>نوع الطلب</th>
                        <th>اسم النشاط</th>
                        <th>الشعبة</th>
                        <th>الشكل القانوني</th>
                        <th>تاريخ الانتهاء</th>
                        <th>نوع النشاط</th>
                        <th> الممثل القانوني  </th>
                        <th> رقم اثبات الهوية  </th>
                        <th> الرقم الوطني  </th>
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
                        <td>' . $company->request_date . '</td>
                        <td>' . $company->request_type . '</td>
                        <td>' . $company->trade_name . '</td>
                        <td>' . ($company->categorydata ? $company->categorydata->name : 'غير محدد') . '</td>
                        <td>' . ($company->companytype ? $company->companytype->name : 'غير محدد') . '</td>
                        <td>' . $expiryDate . '</td>
                        <td>' . ($company->subcategory ? $company->subcategory->name : 'غير محدد') . '</td>
                         <td>' . $company->name . '</td>
                         <td>' . $company->personal_number . '</td>
                         <td>' . $company->id_number . '</td>
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
