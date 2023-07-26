<?php
// Connect to MySQL database
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$products = array();

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $product = array(
      "name" => $row["name"],
      "price" => $row["price"]
    );
    array_push($products, $product);
  }
}

$conn->close();

// Return products as JSON
echo json_encode($products);


// Handle form submission
if (isset($_POST['placeOrder'])) {
  // Retrieve the necessary form data
  $phone = $_POST['phone'];
  $town = $_POST['town'];

  // Calculate the total amount (including shipping fee) for the order
  $totalAmount = calculateTotalAmount();

  // Prepare the M-Pesa payment request
  $consumerKey = 'YOUR_CONSUMER_KEY';
  $consumerSecret = 'YOUR_CONSUMER_SECRET';
  $shortcode = 'YOUR_SHORTCODE';
  $passkey = 'YOUR_PASSKEY';

  $lipaNaMpesaUrl = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
  $timestamp = date('YmdHis');
  $password = base64_encode($shortcode . $passkey . $timestamp);

  $payload = [
    'BusinessShortCode' => $shortcode,
    'Password' => $password,
    'Timestamp' => $timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $totalAmount,
    'PartyA' => $phone,
    'PartyB' => $shortcode,
    'PhoneNumber' => $phone,
    'CallBackURL' => 'YOUR_CALLBACK_URL',
    'AccountReference' => 'YOUR_ACCOUNT_REFERENCE',
    'TransactionDesc' => 'Payment for goods and shipping in ' . $town,
  ];

  $payloadJson = json_encode($payload);

  $headers = [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode($consumerKey . ':' . $consumerSecret),
  ];

  // Send the payment request to M-Pesa
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $lipaNaMpesaUrl);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);

  $response = curl_exec($ch);
  curl_close($ch);

  // Handle the M-Pesa payment response
  // You can process the response and redirect the user to a success or failure page

  // Example handling for success response
  $responseData = json_decode($response, true);
  if ($responseData['ResponseCode'] == '0') {
    // Payment request initiated successfully
    header('Location: success.php');
    exit;
  } else {
    // Payment request failed
    header('Location: failure.php');
    exit;
  }
}

// Function to calculate the total amount for the order
function calculateTotalAmount() {
  // Implement your own logic to calculate the total amount
  // based on the goods in the cart and any additional fees
  // Return the total amount
}



?>