<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Banks extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         DB::table('banks')->truncate();
         DB::statement("INSERT INTO `banks` (`id`, `bank_name`, `sort_code`, `bankname`) VALUES (1, 'SunTrust Bank', 100, NULL), (2, 'First City Monument Bank', 214, NULL), (3, 'Unity Bank', 215, 'UNITY'), (4, 'Stanbic IBTC Bank', 221, 'STANBIC_IBTC'), (5, 'Sterling Bank', 232, 'STERLING'), (6, 'JAIZ Bank', 301, NULL), (7, 'Eartholeum', 302, NULL), (8, 'ChamsMobile', 303, NULL), (9, 'Stanbic Mobile Money', 304, 'STANBIC_MOBILE'), (10, 'Paycom', 305, 'PAYCOM'), (11, 'eTranzact', 306, NULL), (12, 'EcoMobile', 307, NULL), (13, 'FortisMobile', 308, NULL), (14, 'FBNMobile', 309, NULL), (15, 'ReadyCash (Parkway)', 311, 'PARKWAY'), (16, 'Mkudi', 313, NULL), (17, 'FET', 314, NULL), (18, 'GTMobile', 315, NULL), (19, 'Cellulant', 317, NULL), (20, 'Fidelity Mobile', 318, NULL), (21, 'TeasyMobile', 319, NULL), (22, 'VTNetworks', 320, NULL), (23, 'ZenithMobile', 322, 'ZENITH_MOBILE'), (24, 'Access Money', 323, 'ACCESS_MOBILE'), (25, 'Hedonmark', 324, NULL), (26, 'MoneyBox', 325, NULL), (27, 'Sterling Mobile', 326, NULL), (28, 'Pagatech', 327, NULL), (29, 'TagPay', 328, NULL), (30, 'PayAttitude Online', 329, NULL), (31, 'ASO Savings', 401, 'ASO'), (32, 'Jubilee Life Mortgage Bank', 402, NULL), (33, 'SafeTrust Mortgage Bank', 403, NULL), (34, 'Fortis Microfinance Bank', 501, NULL), (35, 'Trustbond', 523, NULL), (36, 'Parralex', 526, NULL), (37, 'Covenant Microfinance Bank', 551, NULL), (38, 'NPF MicroFinance Bank', 552, NULL), (39, 'Coronation Merchant Bank', 559, NULL), (40, 'FSDH', 601, NULL), (41, 'Test Bank', 990, NULL), (42, 'NIP Virtual Bank', 999, NULL), (43, 'Fidelity Bank', 70, 'FIDELITY'), (44, 'Heritage', 30, 'HERITAGE'), (45, 'Skye Bank', 76, 'SKYE'), (46, 'Union Bank', 32, 'UNION'), (47, 'United Bank for Africa', 33, 'UBA'), (48, 'Wema Bank', 35, 'WEMA'), (49, 'Keystone Bank', 82, 'KEYSTONE'), (50, 'Enterprise Bank', 84, 'ENTERPRISE'), (51, 'Access Bank', 44, 'ACCESS'), (52, 'Ecobank Plc', 50, 'ECOBANK'), (53, 'First Bank of Nigeria', 11, 'FIRST'), (54, 'Zenith Bank', 57, 'ZENITH'), (55, 'GTBank Plc', 58, 'GTBANK'), (56, 'Diamond Bank', 63, 'DIAMOND'), (57, 'CitiBank', 23, NULL), (58, 'Standard Chartered Bank', 68, 'STANDARD_CHARTERED');");       
    }
}
