<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){

$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$city = $_POST['city'];
$state = $_POST['state'];
$zipcode = $_POST['zipcode'];
$qty = $_POST['quantity'];
$total = $_POST['total_price'];
$card = $_POST['card_number'];
$expiry = $_POST['expiry'];
$cvv = $_POST['cvv'];

$sql = "INSERT INTO orders
(last_name,email,phone,address,city,state,zipcode,ticket_qty,total_price,card_number,expiry,cvv)
VALUES
('$last_name','$email','$phone','$address','$city','$state','$zipcode','$qty','$total','$card','$expiry','$cvv')";

$conn->query($sql);

echo "Order placed successfully!";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Checkout</title>

    <style>
        body {
            font-family: Arial;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 700px;
            margin: 40px auto;
            background: #31C3C9;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            height: 1800px;
        }

        h2 {
            text-align: center;
        }

        h3 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #BAD67F;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }

        .review {
            background: rgb(255, 255, 255);
            padding: 15px;
            border-radius: 5px;
        }

        .discount-info {
            font-size: 14px;
            color: black;
        }

        /* ---The top Navigation Bar----- */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #BAD67F;
            padding: 10px 30px;
        }

        .logo {
            margin-left: -19px;
        }

        .logo img {
            height: 100px;
            width: auto;
            display: block;
            border-radius: 10px;
        }

        /* -----My Account Button----- */
        .account-btn {
            display: flex;
            align-items: center;
            gap: 2px;
            background-color: #f4b942;
            padding: 0 20px;
            height: 40px;
            border-radius: 999px;

            text-decoration: none;
            color: #000;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.2s ease;
            flex-shrink: 0;
        }

        /* ---My Account button Icon---- */
        .account-btn img {
            height: 40px;
            width: auto;
            object-fit: contain;
            display: block;
            transform: translateX(-6px);
        }

        .account-btn span {
            margin-left: -25px;
        }

        .account-btn:hover {
            background-color: #cfa13f;
        }

        /* ----App Layout wrapper for pages with a sidebar----- */
        .app-layout {
            display: flex;
            min-height: calc(100vh - 70px);
        }

        /* ---Left sidebar navigation--- */
        .side-nav {
            width: 200px;
            background-color: #BAD67F;
            padding: 24px 12px;
            box-sizing: border-box;

            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        /* ---Nav sidebar links--- */
        .nav-item {
            display: flex;
            align-items: center;
            justify-content: center;

            text-align: center;
            text-decoration: none;
            color: #000000;
            font-weight: 600;
            font-size: 14px;

            padding: 10px 10px;
            border-radius: 14px;
            white-space: nowrap;
        }


        /* ---Nav Sidebar hover + active state--- */
        .nav-item:hover {
            background: rgba(255, 255, 255, 0.18);
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.26);
            font-weight: 700;
        }


        /* ---Main content area next to sidebar--- */
        .app-layout>main {
            flex: 1;
        }

        /* Search bar stuff */
        /* Top header search bar (shared) */
        .top-bar {
            gap: 16px;
        }

        .top-search {
            flex: 1;
            display: flex;
            justify-content: left;
        }

        .top-search input {
            width: 100%;
            max-width: 520px;
            height: 38px;
            border-radius: 6px;
            border: 1px solid rgba(0, 0, 0, 0.2);
            padding: 0 12px;
            outline: none;
        }

        .top-search input:focus {
            border-color: #6b7676;
        }
    </style>

    <script>

        function updateTotal() {

            let ticketPrice = 30;
            let qty = parseInt(document.getElementById("quantity").value);
            let subtotal = ticketPrice * qty;

            let discount = 0;
            let code = document.getElementById("discount").value.toLowerCase();

            if (code == "gator") {
                discount = subtotal * 0.15;
            }

            else if (code == "10off") {
                discount = 10;
            }

            else if (code == "welcome") {
                discount = subtotal * 0.30;
            }

            else if (code == "bogo") {

                let freeTickets = Math.floor(qty / 2);
                discount = freeTickets * ticketPrice;

            }

            let total = subtotal - discount;

            if (total < 0) {
                total = 0;
            }

            document.getElementById("subtotal").innerHTML = "$" + subtotal.toFixed(2);
            document.getElementById("discount_amount").innerHTML = "- $" + discount.toFixed(2);
            document.getElementById("total").innerHTML = "$" + total.toFixed(2);

            document.getElementById("total_price").value = total.toFixed(2);

        }

    </script>

</head>

<body onload="updateTotal()">

    <header class="top-bar">
        <a class="logo" href="../pages/home/home.html">
            <img src="../images/Explorlando-Logo.PNG" alt="Explorlando logo">
        </a>

        <form class="top-search" role="search">
            <input type="search" placeholder="Search..." aria-label="Search Explorlando">
        </form>

        <a href="profile.html" class="account-btn">
            <img src="../images/user-1.png" alt="Account icon">
            <span>My Account</span>
        </a>
    </header>

    <div class="app-layout">

        <!-- Left Sidebar Nav -->
        <nav class="side-nav">
            <a class="nav-item" href="login.html">Login</a>
            <a class="nav-item active" href="profile.html">Profile</a>
            <a class="nav-item" href="../aboutus.html">About Us</a>
            <a class="nav-item" href="../ms5/pages/attractions.html">Attractions</a>
            <a class="nav-item" href="../pages/open-lobby.html">Open Lobby</a>
            <a class="nav-item" href="../pages/spotlight/spotlight.html">Explorlando Spotlight</a>
            <a class="nav-item" href="../pages/for_business/for_business.html">For Businesses</a>
            <a class="nav-item" href="../ms5/pages/settings.html">Settings</a>
        </nav>

        <div class="container">

            <h2>Checkout</h2>


            <form method="POST">

                <h3>Contact Information</h3>

                Last Name<br>
                <input type="text" name="last_name" required><br><br>

                Email Address<br>
                <input type="email" name="email" required><br><br>

                Phone Number<br>
                <input type="text" name="phone" required><br><br>


                <h3>Billing Address</h3>

                Address<br>
                <input type="text" name="address" required><br><br>

                City<br>
                <input type="text" name="city" required><br><br>

                State<br>
                <input type="text" name="state" required><br><br>

                Zip Code<br>
                <input type="text" name="zipcode" required><br><br>


                <h3>Tickets</h3>

                Ticket Price: $30<br><br>

                Quantity<br>
                <input type="number" id="quantity" name="quantity" value="1" min="1" onchange="updateTotal()"><br><br>


                <h3>Discount Code</h3>

                <input type="text" id="discount" onkeyup="updateTotal()" placeholder="Enter code"><br><br>

                Available Codes:<br>
                gator (15% off)<br>
                10off ($10 off)<br>
                welcome (30% off)<br>
                bogo (Buy One Get One)<br><br>


                <h3>Payment</h3>

                Card Number<br>
                <input type="text" name="card_number" required><br><br>

                Expiry (MM/YY)<br>
                <input type="text" name="expiry" required><br><br>

                CVV<br>
                <input type="text" name="cvv" required><br><br>


                <h3>Order Review</h3>

                Subtotal: <span id="subtotal"></span><br>
                Discount: <span id="discount_amount"></span><br>
                Total: <span id="total"></span><br><br>

                <input type="hidden" name="total_price" id="total_price">

                <button type="submit">Place Order</button>

            </form>
            </div>
            </div>
</body>

</html>
