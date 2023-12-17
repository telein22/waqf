<?php

namespace Application\ThirdParties\QRcode;

use chillerlan\QRCode\{QRCode, QROptions};
use Application\Helpers\AppHelper;

class QRcodeGenerator
{
    const CONTENT_TYPE_PROFILE = 'profile';
    const CONTENT_TYPE_POSTER = 'poster';

    public static function generateQrCode(int $id, string $type = 'profile'): void
    {
        $qrCodeContent = AppHelper::getBaseUrl() . "/outer-profile/{$id}";

        if ($type == self::CONTENT_TYPE_POSTER) {
            $qrCodeContent = AppHelper::getBaseUrl() . "/feed/{$id}";
        }

        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_L,
            'scale' => 4,
            'imageBase64' => false,
        ]);

        $qrCode = new QRCode($options);
        $qrCodeImagePath = "Storage/BusinessCards/qr_code_{$type}_{$id}.png";
        $qrCode->render($qrCodeContent, $qrCodeImagePath);
    }
}
