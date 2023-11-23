<?php

namespace App\Enums;

enum RecruitmentResultEnum: string
{
    case take_job = 'Nhận việc';
    case reject_take_job = 'Không nhận việc';
}