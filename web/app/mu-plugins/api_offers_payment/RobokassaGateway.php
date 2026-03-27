<?php
namespace App\OffersPayment;

use Exception;

class RobokassaGateway
{
    protected string $merchantLogin;
    protected string $password1;
    protected string $password2; // Needed for result verification
    protected string $isTest;

    public function __construct()
    {
        $this->merchantLogin = function_exists('env') ? env('ROBOKASSA_MERCHANT_LOGIN', '') : ($_ENV['ROBOKASSA_MERCHANT_LOGIN'] ?? '');
        $this->password1 = function_exists('env') ? env('ROBOKASSA_PASSWORD_1', '') : ($_ENV['ROBOKASSA_PASSWORD_1'] ?? '');
        $this->password2 = function_exists('env') ? env('ROBOKASSA_PASSWORD_2', '') : ($_ENV['ROBOKASSA_PASSWORD_2'] ?? '');
        $this->isTest = function_exists('env') ? env('ROBOKASSA_IS_TEST', '1') : ($_ENV['ROBOKASSA_IS_TEST'] ?? '1');

        if (empty($this->merchantLogin)) {
            // throw new Exception("Robokassa configuration is missing.");
        }
    }

    private function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function createCheckoutUrl(int $inv_id, float $price, string $description): string
    {
        $out_summ = number_format($price, 2, '.', '');
        $is_test  = (int) $this->isTest;

        // Classic Robokassa signature: md5(MerchantLogin:OutSum:InvId:Password1)
        $signature = md5("{$this->merchantLogin}:{$out_summ}:{$inv_id}:{$this->password1}");

        $params = [
            'MerchantLogin'  => $this->merchantLogin,
            'OutSum'         => $out_summ,
            'InvId'          => $inv_id,
            'Description'    => mb_substr($description, 0, 100),
            'SignatureValue'  => $signature,
            'Encoding'       => 'utf-8',
            'Culture'        => 'ru',
        ];

        if ($is_test) {
            $params['IsTest'] = 1;
        }

        return 'https://auth.robokassa.ru/Merchant/Index.aspx?' . http_build_query($params);
    }

    public function verifyResultSignature($outSum, $invId, $signature): bool
    {
        $my_signature = strtoupper(md5("{$outSum}:{$invId}:{$this->password2}"));
        return strtoupper($signature) === $my_signature;
    }
}
