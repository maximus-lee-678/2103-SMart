<?php
session_start();

include "helper-functions.php";

//[FUNCTIONS]///////////////////////////////////////////////////////////////////////////////////////
function get_staff_role() {

    $staff_id = 5;
    $role_ids = array();

    $conn = make_connection();

    $query = 'SELECT role_id FROM RoleAssignment WHERE staff_id = ?';
    $result = payload_deliver($conn, $query, "i", $params = array($staff_id));

    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($role_ids, $row["role_id"]);
        }
        $conn->close();
        return $role_ids;
    } else {
        $conn->close();
        return 0;
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////
$staff_roles = get_staff_role();
?>

<script defer src="js/employee-home.js"></script>

<html lang="en">

    <title>IT Admin Home Page</title>

    <?php
    include "head.php";
    ?>
    <body>

        <!-- header section starts -->
        <?php include "nav.php"; ?>
        <!-- header section ends -->

        <div class="overlay"></div>
        <span class="popup" hidden></span>

        <div class="heading">
            <h1>Employee Dashboard</h1>
        </div>

        <section class="contact" style="margin-bottom: 1350px; margin-top: 50px; font-size: 1.4rem; color: #666;">

            <div class="tab">
                <?php
                if (in_array(1, $staff_roles, true)) {
                    echo '<button class="tablinks" onclick="openCity(event, \'myStaff\')">Manage Staff Accounts</button>';
                }
                if (in_array(2, $staff_roles, true)) {
                    echo '<button class="tablinks" onclick="openCity(event, \'myProducts\')">View All Products</button>
                        <button class="tablinks" onclick="openCity(event, \'editsupermarkettable\')">Edit Supermarket</button>
                        <button class="tablinks" onclick="openCity(event, \'editCategory\')">Edit Category</button>
                        <button class="tablinks" onclick="openCity(event, \'editbrand\')">Edit Brand</button>';
                }
                if (in_array(3, $staff_roles, true)) {
                    echo '<button class="tablinks" onclick="openCity(event, \'viewproductstock\')">View Product Stock</button>
                    ';
                }
                if (in_array(4, $staff_roles, true)) {
                    echo '<button class="tablinks" onclick="openCity(event, \'viewpacking\')">View All Items to Pack</button>
                    ';
                }
                if (in_array(5, $staff_roles, true)) {
                    echo '<button class="tablinks" onclick="openCity(event, \'deliveryinformation\')">View All Items to Deliver</button>
                    ';
                }
                if (in_array(6, $staff_roles, true)) {
                    echo '<button class="tablinks" onclick="openCity(event, \'orderinformation\')">View All Order Information</button>
                    ';
                }
                ?>
            </div>

            <?php
            if (in_array(1, $staff_roles, true)) {
                echo '<div id="myStaff" class="tabcontent">
                        <div class="row">
                            <form action="#" class="register-form" method="post" name="mystaffroleform">

                                <h3>Manage Staff Accounts</h3>

                                <span id="staff-contents">
                                </span>

                            </form>
                        </div>
                    </div>';
            }
            ?>

            <?php
            if (in_array(2, $staff_roles, true)) {
                echo '<div id="myProducts" class="tabcontent">
                        <div class="row">

                            <form action="#" class="register-form" method="post" name="myproductform">

                                <h3>View All Products</h3>

                                <span id="product-contents">
                                </span>

                            </form>

                        </div>
                    </div>

                    <div id="editsupermarkettable" class="tabcontent">
                        <div class="row">

                            <form action="#" class="register-form" method="post" name="editsupermarketform">

                                <h3>Edit Supermarket</h3>

                                <span id="supermarket-contents">
                                </span>

                            </form>
                        </div>
                    </div>

                    <div id="editCategory" class="tabcontent">
                        <div class="row">

                            <form action="#" class="register-form" method="post" name="editcategoryform">

                                <h3>Edit Category</h3>

                                <span id="category-contents">
                                </span>

                            </form>

                        </div>
                    </div>

                    <div id="editbrand" class="tabcontent">
                        <div class="row">

                            <form action="#" class="register-form" method="post" name="editbrandform">

                                <h3>Edit Brand</h3>

                                <span id="brand-contents">
                                </span>

                            </form>

                        </div>
                    </div>';
            }
            ?>

            <?php
            if (in_array(3, $staff_roles, true)) {
                echo '<div id="viewproductstock" class="tabcontent">
                        <div class="row">

                            <form action="#" class="register-form" method="post" name="viewallstocks">

                                <h3>View All Stock</h3>

                                <span id="stock-contents">
                                </span>

                            </form>

                        </div>
                    </div>';
            }
            ?>

            <?php
            if (in_array(4, $staff_roles, true)) {
                echo '<div id="viewpacking" class="tabcontent">
                        <div class="row">

                            <form action="#" class="register-form" method="post" name="viewallorders">

                                <h3>View All Items to Pack</h3>

                                <span id="pack-contents">
                                </span>

                            </form>

                        </div>
                    </div>';
            }
            ?>

            <?php
            if (in_array(5, $staff_roles, true)) {
                echo '<div id="deliveryinformation" class="tabcontent">
                        <div class="row">

                            <form action="#" class="register-form" method="post" name="deliveryinformation">

                                <h3>View All Items to Deliver</h3>

                                <span id="delivery-contents">
                                </span>

                            </form>

                        </div>
                    </div>';
            }
            ?>

            <?php
            if (in_array(6, $staff_roles, true)) {
                echo '<div id="orderinformation" class="tabcontent">
                        <div class="row">

                            <form action="#" class="register-form" method="post" name="viewallorderinformatio ">
                                <h3>View All Order Information</h3>

                                <span id="order_all-contents">
                                </span>
                            </form>

                        </div>
                    </div>';
            }
            ?>

        </section>

        <?php include "footer.php"; ?>

    </body>
</html>
