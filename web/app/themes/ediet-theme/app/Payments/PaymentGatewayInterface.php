<?php

namespace App\Payments;

interface PaymentGatewayInterface
{
    /**
     * Create a checkout URL for the specified offer.
     *
     * @param int $offer_id The ID of the personal_offer post.
     * @param float $price The price to charge.
     * @param int $product_id The ID of the target product.
     * @return string The redirect URL for the payment.
     * @throws \Exception If the gateway fails to generate the URL.
     */
    public function createCheckoutUrl(int $offer_id, float $price, int $product_id): string;
}
