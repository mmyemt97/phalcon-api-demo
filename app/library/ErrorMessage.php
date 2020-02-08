<?php
namespace Website;

use SVCodebase\Library\ErrorMessages;

class ErrorMessage extends ErrorMessages
{
    const INVALID_DATA_TO_CREATE = 'Dữ liệu đã tồn tại. Không thể tạo mới.';
    const INVALID_DATA_TO_UPDATE = 'Dữ liệu không đồng nhất. Không thể cập nhật.';
    const EXCEED_MAX = 'Dữ liệu đã vượt mức cho phép.';
}