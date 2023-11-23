<?php

namespace App\Enums;

enum BranchEnum: string
{
    case formal = 'Chính quy';
    case e_learning = 'Vừa làm vừa học';
    case remote = 'Từ xa';
    case university_transfer = 'Liên thông';
    case x = 'x';
}