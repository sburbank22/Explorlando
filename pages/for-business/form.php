<?php
$something_missing = 0;

if (isset($_POST['submit'])) {
    if (empty($_POST['name'])) { $something_missing = 1; }
    else if (empty($_POST['businessName'])) { $something_missing = 1; }
    else if (empty($_POST['attractionName'])) { $something_missing = 1; }
    else if (empty($_POST['email'])) { $something_missing = 1; }
    else if (empty($_POST['phone'])) { $something_missing = 1; }
    else if (empty($_POST['budget'])) { $something_missing = 1; }
    else { $something_missing = 0; }

    if (isset($_POST['name'])) { setcookie("name", $_POST['name'], time() + 30); }
    if (isset($_POST['businessName'])) { setcookie("businessName", $_POST['businessName'], time() + 30); }
    if (isset($_POST['attractionName'])) { setcookie("attractionName", $_POST['attractionName'], time() + 30); }
    if (isset($_POST['email'])) { setcookie("email", $_POST['email'], time() + 30); }
    if (isset($_POST['phone'])) { setcookie("phone", $_POST['phone'], time() + 30); }
    if (isset($_POST['budget'])) { setcookie("budget", $_POST['budget'], time() + 30); }

    if ($something_missing == 0) {
        require_once "../../api/db.php";

        $stmt = $conn->prepare("INSERT INTO submissions (name, business_name, attraction_name, email, phone, budget) VALUES (:name, :businessName, :attractionName, :email, :phone, :budget)");
        $stmt->execute([
            ':name' => $_POST['name'],
            ':businessName' => $_POST['businessName'],
            ':attractionName' => $_POST['attractionName'],
            ':email' => $_POST['email'],
            ':phone' => $_POST['phone'],
            ':budget' => $_POST['budget']
        ]);

        header("Location: submit.html");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partnership Request Form - Explorlando</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/for-business.css">
    <script src="../../js/for-business.js"></script>
</head>

<body>

    <header class="top-bar">
        <a class="logo" href="../home/home.html">
            <img src="../../images/Explorlando-Logo.PNG" alt="Explorlando logo">
        </a>

        <form class="top-search" role="search">
            <input type="search" placeholder="Search..." aria-label="Search Explorlando">
        </form>

        <a href="../profile/profile.html" class="account-btn">
            <img src="../../images/user-1.png" alt="Account icon">
            <span>My Account</span>
        </a>
    </header>

    <div class="app-layout">
        <nav class="side-nav">
            <a class="nav-item" href="../auth/login.html">Login</a>
            <a class="nav-item" href="../profile/profile.html">Profile</a>
            <a class="nav-item" href="../about.html">About Us</a>
            <a class="nav-item" href="../attractions/index.html">Attractions</a>
            <a class="nav-item" href="../open-lobby/index.html">Open Lobby</a>
            <a class="nav-item" href="../spotlight/spotlight.html">Explorlando Spotlight</a>
            <a class="nav-item active" href="index.html">For Businesses</a>
            <a class="nav-item" href="../profile/settings.html">Settings</a>
        </nav>

        <main class="main-content">
            <section class="page-hero">
                <h1>Explorlando Partnership Request Form</h1>
            </section>

            <section class="form-section">
                <div class="form-intro">
                    <p>Fill out the following with the appropriate information to request an advertising partnership
                        with Explorlando!</p>
                </div>

                <form id="partnershipForm" class="partnership-form" action="form.php" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Joe Smith" value="<?php if (isset($_COOKIE['name'])) { print $_COOKIE['name']; } ?>">
                        <?php if (empty($_POST['name']) && isset($_POST['submit'])) { ?><p>Please enter a value for Name!</p><?php } ?>
                    </div>

                    <div class="form-group">
                        <label for="businessName">Business Name:</label>
                        <input type="text" id="businessName" name="businessName" placeholder="Old Joe's Inc." value="<?php if (isset($_COOKIE['businessName'])) { print $_COOKIE['businessName']; } ?>">
                        <?php if (empty($_POST['businessName']) && isset($_POST['submit'])) { ?><p>Please enter a value for Business Name!</p><?php } ?>
                    </div>

                    <div class="form-group">
                        <label for="attractionName">Attraction Name:</label>
                        <input type="text" id="attractionName" name="attractionName" placeholder="Joe's Local Crab Shack" value="<?php if (isset($_COOKIE['attractionName'])) { print $_COOKIE['attractionName']; } ?>">
                        <?php if (empty($_POST['attractionName']) && isset($_POST['submit'])) { ?><p>Please enter a value for Attraction Name!</p><?php } ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Business Email:</label>
                        <input type="text" id="email" name="email" placeholder="JoeSmith@yahoo.com" value="<?php if (isset($_COOKIE['email'])) { print $_COOKIE['email']; } ?>">
                        <?php if (empty($_POST['email']) && isset($_POST['submit'])) { ?><p>Please enter a value for Business Email!</p><?php } ?>
                    </div>

                    <div class="form-group">
                        <label for="phone">Business Phone:</label>
                        <input type="text" id="phone" name="phone" placeholder="(555) 555-5555" value="<?php if (isset($_COOKIE['phone'])) { print $_COOKIE['phone']; } ?>">
                        <?php if (empty($_POST['phone']) && isset($_POST['submit'])) { ?><p>Please enter a value for Business Phone!</p><?php } ?>
                    </div>

                    <div class="form-group">
                        <label for="budget">Estimated Marketing Budget (Example Format: $1000):</label>
                        <input type="text" id="budget" name="budget" placeholder="$2000" value="<?php if (isset($_COOKIE['budget'])) { print $_COOKIE['budget']; } ?>">
                        <?php if (empty($_POST['budget']) && isset($_POST['submit'])) { ?><p>Please enter a value for Estimated Marketing Budget!</p><?php } ?>
                    </div>

                    <div class="form-group">
                        <label for="content">Browse Marketing Content (user selects (4 x 3) images or 2160 x 1620 pixels):</label>
                        <input type="file" id="content" name="content" accept="image/*">
                    </div>

                    <button type="submit" name="submit" class="cta-button green-button">Submit Form</button>
                </form>
            </section>
        </main>
    </div>

</body>

</html>