<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require_once '../../vendor/autoload.php';
require_once '../secrets.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);

function calculateOrderAmount(array $items): int {
    // Replace this constant with a calculation of the order's amount
    // Calculate the order total on the server to prevent
    // people from directly manipulating the amount on the client
    return 1400;
}

try {
    $stripe = new \Stripe\StripeClient(
        $stripeSecretKey
    );
    $data = json_decode(file_get_contents("php://input"));
    $intent = $stripe->paymentIntents->retrieve(
        'pi_3MUWTEGKuCg1RS3J1ESsBNo1',
        []
    );
    echo json_encode($intent);

} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}