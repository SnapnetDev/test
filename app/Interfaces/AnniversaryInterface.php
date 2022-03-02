<?php


namespace App\Interfaces;



interface AnniversaryInterface
{

    public function  sendPublicHolidayReminder($user);
    public function  sendWeddindingReminder();
    public function  sendBirthDayReminder();
    public function checkBirthday(string $birthDay);
    public function greetUser( $userDetail);

}
