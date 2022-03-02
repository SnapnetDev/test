<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDF;
use App\PayrollDetail;
use App\Payroll;
use App\PayrollPolicy;

class ZipPayslips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payslip:zip {company_id} {month} {year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $company_id = $this->argument('company_id');
        $pmonth = $this->argument('month');
        $pyear = $this->argument('year');

        $company = \App\Company::find($company_id);
//
//
        $pp = PayrollPolicy::where('company_id', $company_id)->first();
        $payroll = Payroll::where(['month' => $pmonth, 'year' => $pyear, 'company_id' => $company_id])->first();
//
//        // return view('compensation.partials.payslip2', compact('detail','company'));
//        //return '<img src="'.url('uploads/logo').$company->logo.'" style="height: 2rem;background-color:#fff; width: auto;">';
        if ($payroll) {
            foreach ($payroll->payroll_details as $detail) {
                if ($pp->show_all_gross == 1) {
                    // dd(1);
                    $pdf = PDF::loadView('compensation.partials.payslip', compact('detail', 'company'));
//                    $pdf->setWatermarkImage($image, $opacity = 0.3, $top = '30%', $width = '100%', $height = '100%');
                } else {
                    // dd(2);
                    $pdf = PDF::loadView('compensation.partials.payslip2', compact('detail', 'company'));
//                    $pdf->setWatermarkImage($image, $opacity = 0.3, $top = '30%', $width = '100%', $height = '100%');
                }

                $content = $pdf->download()->getOriginalContent();

                \Storage::put('invoices/'.$detail->user->name.'.pdf', $content);

            }

            $zip_file = 'invoices.zip';
            $zip = new \ZipArchive();

            if ($zip->open(public_path($zip_file), \ZipArchive::CREATE) === TRUE) {
                // $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                $path =public_path() . '/uploads';
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
                foreach ($files as $name => $file) {
                    // We're skipping all subfolders
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();

                        // extracting filename with substr/strlen
                        $relativePath = 'invoices/' . substr($filePath, strlen($path) + 1);

                        $zip->addFile($filePath, $relativePath);
                    }
                }
                $zip->close();
            }
            return $this->info('Zip Completed');
        }else{
            return $this->info('Payroll has not been run');
        }
//        return response()->download(public_path($zip_file));
    }
}
