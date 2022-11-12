<?php
session_start();

function get_staff_role() {

    $staff_id = 5;
    $role_ids = array();

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error;
        return 0;
    }

    // Prepare the statement:
    $stmt = $conn->prepare("SELECT role_id FROM RoleAssignment WHERE staff_id = ?");
    // Bind & execute the query statement:
    $stmt->bind_param("i", $staff_id);
    // execute the query statement:
    if (!$stmt->execute()) {
        echo "Failed while executing query: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $stmt->close();
        $conn->close();
        return 0;
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($role_ids, $row["role_id"]);
        }
        $stmt->close();
        $conn->close();
        return $role_ids;
    } else {
        $stmt->close();
        $conn->close();
        return 0;
    }

    $stmt->close();
    $conn->close();
    return 0;
}

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
                    echo '<button class="tablinks" onclick="openCity(event, \'viewproductstock\')">View Product Stock</button>';
                }
                if (in_array(4, $staff_roles, true)) {
                    echo '<button class="tablinks" onclick="openCity(event, \'viewpacking\')">View All Items to Pack</button>';
                }
                if (in_array(5, $staff_roles, true)) {
                    echo '<button class="tablinks" onclick="openCity(event, \'deliveryinformation\')">View All Items to Deliver</button>';
                }
                if (in_array(6, $staff_roles, true)) {
                    echo '<button class="tablinks" onclick="openCity(event, \'orderinformation\')">View All Order Information</button>';
                }
                ?>
            </div>

            <div id="myStaff" class="tabcontent">
                <div class="row">

                    <form action="#" class="register-form" method="post" name="mystaffroleform">

                        <h3>Manage Staff Accounts</h3>

                        <span id="staff-contents">
                        </span>

                        <!--                        <h4 style="margin-top: 50px; font-size: 2.4rem;">Current Role(s) for: (Jonny Tham)</h4>
                        
                                                <table class="carttable" style="font-size: 1.4rem; margin-top: 30px; width: 50%;">
                                                    <tr style="text-align: center; background: #b5838d; color: white;">
                                                        <td>ID</td>
                                                        <td>Role</td>
                                                        <td></td>
                                                    </tr>
                                                    <tr style="text-align: center; background: white;">
                                                        <td>1</td>
                                                        <td>Admin</td>
                                                        <td><a href="#" style="color: #bac34e;">Remove Role</a></td>
                                                    </tr>
                                                    <tr style="text-align: center; background: white;">
                                                        <td>2</td>
                                                        <td>Packer</td>
                                                        <td><a href="#" style="color: #bac34e;">Remove Role</a></td>
                                                    </tr>
                                                </table>
                        
                                                <div class="inputBox" style="margin-top: 30px;">
                                                    <input id="user_payid" name="user_payid" type="hidden">
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <p>Roles: </p>
                                                    </div>
                                                    <select required style="width: 100%; color: #666;" name="user_payment_type" id="user_payment_type" class="box">
                                                        <option value="">- Choose a Role -</option>
                                                        <option value="Customer">Customer</option>
                                                        <option value="Admin">Admin</option>
                                                        <option value="Product Manager">Product Manager</option>
                                                        <option value="Warehouse Manager">Warehouse Manager</option>
                                                        <option value="Packer">Packer</option>
                                                        <option value="Delivery Man">Delivery Man</option>
                                                        <option value="Order Manager">Order Manager</option>
                                                    </select>
                                                    <input type="button" style="width: 30%; margin-top: 15px;" id="addthisrole" name="addthisrole" value="Add This Role" class="btn">
                                                </div>
                        
                                                <div class="inputBox" style="margin-top: 40px;">
                                                    <input type="button" style="width: 100%" id="updaterole" name="updaterole" value="Update This Record" class="btn">
                                                </div>-->

                    </form>

                    <!--                    <form action="#" class="register-form" method="post" name="addstaffform">
                    
                                            <h3>Add Staff Account</h3>
                    
                                            <table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                                                <tr style="text-align: center; background: #6D6875; color: white;">
                                                    <th>ID</th>
                                                    <th colspan="2">Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Role</th>
                                                </tr>
                    
                                                <tr style="text-align: center;">
                                                    <td>1</td>
                                                    <td>Jonny</td>
                                                    <td>Tham</td>
                                                    <td>JonnyTham@gmail.com</td>
                                                    <td>99998888</td>
                                                    <td>Admin & Packer</td>
                                                </tr>
                    
                                            </table>
                    
                    
                                            <div style="margin-bottom: 15px; margin-top: 40px;">
                                                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                    <label style="width: 49%">First Name: </label>
                                                    <label style="width: 49%">Last Name: </label>
                                                </div>
                                                <div class="inputBox">
                                                    <input readonly=false style="width: 49%" id="user_firstname" name="user_firstname" type="text" class="box" value="">
                                                    <input readonly=false style="width: 49%" id="user_lastname" name="user_lastname" type="text" class="box" value="">
                                                </div>
                                            </div>
                    
                                            <div style="margin-bottom: 15px;">
                                                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                    <label style="width: 49%">Phone No: </label>
                                                    <label style="width: 49%">Email: </label>
                                                </div>
                                                <div class="inputBox">
                                                    <input readonly=false style="width: 49%" id="user_phonenum" name="user_phonenum" type="text" class="box" value="">
                                                    <input readonly=false style="width: 49%" id="user_email" name="user_email" type="email" class="box" value="">
                                                </div>
                                            </div>
                    
                                            <div class="inputBox">
                                                <input type="button" style="width: 98%" id="addnewemployee" name="addnewemployee" value="Add New Employee" class="btn">
                                            </div>
                    
                                        </form>-->

                </div>
            </div>


            <div id="myProducts" class="tabcontent">
                <div class="row">

                    <form action="#" class="register-form" method="post" name="myproductform">

                        <h3>View All Products</h3>

                        <span id="product-contents">
                        </span>

                        <!--                        
                                                <div style="margin-bottom: 15px; margin-top: 80px;">
                                                    <img src="image/category/drinks.png" alt="product image" width="200" height="200" style="margin-top: 20px; margin-bottom: 20px;">
                                                </div>
                        
                                                <div style="margin-bottom: 20px; margin-top: 20px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 98%">Product ID: </label>                                
                                                    </div>
                                                    <div class="inputBox">
                                                        <input readonly="true" style="width: 98%" id="productid" name="productid" type="text" placeholder="Enter the Product ID" class="box" maxlength="250">
                                                    </div>
                                                </div>
                        
                                                <div style="margin-bottom: 20px; margin-top: 20px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 98%">Product Name: </label>                                
                                                    </div>
                                                    <div class="inputBox">
                                                        <input required style="width: 98%" id="productname" name="productname" type="text" placeholder="Enter the Product Name" class="box" maxlength="250">
                                                    </div>
                                                </div>
                        
                        
                                                <div style="margin-bottom: 20px; margin-top: 20px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 32%">Unit: </label>
                                                        <label style="width: 32%">Price: </label>
                                                        <label style="width: 32%">Quantity: </label>
                                                    </div>
                                                    <div class="inputBox">
                                                        <input required style="width: 32%" id="productunit" name="productunit" type="text" placeholder="Enter Product Unit" class="box" maxlength="10">
                                                        <input required style="width: 32%" id="productprice" name="productprice" type="text" placeholder="Enter Product Price" class="box" maxlength="10">
                                                        <input required style="width: 32%" id="productquantity" name="productquantity" type="text" placeholder="Enter Product Qauntity" class="box" maxlength="6">
                                                    </div>
                                                </div>
                        
                                                <div style="margin-bottom: 20px; margin-top: 20px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 32%">Supermarket: </label>
                                                        <label style="width: 32%">Category: </label>
                                                        <label style="width: 32%">Brand: </label>
                                                    </div>
                                                    <div class="inputBox">
                                                        <input required style="width: 32%" id="productsupermarket" name="productsupermarket" type="text" placeholder="Enter Product Unit" class="box" maxlength="250">
                                                        <input required style="width: 32%" id="productcategory" name="productcategory" type="text" placeholder="Enter Product Price" class="box" maxlength="250">
                                                        <input required style="width: 32%" id="productbrand" name="productbrand" type="text" placeholder="Enter Product Qauntity" class="box" maxlength="250">
                                                    </div>
                                                </div>
                        
                        
                                                <div style="margin-bottom: 15px;">
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 49%">Last Restocked Date: </label>
                                                        <label style="width: 49%">Last Restocked By: </label>
                                                    </div>
                                                    <div class="inputBox">
                                                        <input required style="width: 49%; font-size: 1.4rem; color: #666;" id="product_lastrestockeddate" name="product_lastrestockeddate" type="date" placeholder="Enter Date" class="box">
                                                        <input required style="width: 49%" id="product_lastrestockedby" name="product_lastrestockedby" type="text" class="box" placeholder="Enter Name" class="box" maxlength="250">
                                                    </div>
                                                </div>
                        
                        
                                                <div class="inputBox">
                                                    <input type="button" style="width: 32%" id="updateproductinfo" name="updateproductinfo" value="Update" class="btn">
                                                    <input type="button" style="width: 32%" id="addproductinfo" name="addproductinfo" value="Add" class="btn">
                                                    <input type="button" style="width: 32%" id="deleteproductinfo" name="deleteproductinfo" value="Delete" class="btn">
                                                </div>-->
                    </form>

                </div>
            </div>


            <div id="editsupermarkettable" class="tabcontent">
                <div class="row">

                    <form action="#" class="register-form" method="post" name="editsupermarketform">

                        <h3>Edit Supermarket</h3>

                        <span id="supermarket-contents">
                        </span>

                        <!--                        <div style="margin-bottom: 20px; margin-top: 40px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 98%">Supermarket ID: </label>                                
                                                    </div>
                                                    <div class="inputBox">
                                                        <input readonly="true" style="width: 98%" id="supermarketid" name="supermarketid" type="text" placeholder="Enter the Supermarket ID" class="box" maxlength="6">
                                                    </div>
                                                </div>
                        
                                                <div style="margin-bottom: 20px; margin-top: 20px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 98%">Supermarket Name: </label>                                
                                                    </div>
                                                    <div class="inputBox">
                                                        <input required style="width: 98%" id="supermarketname" name="supermarketname" type="text" placeholder="Enter the Supermarket Name" class="box" maxlength="250">
                                                    </div>
                                                </div>
                        
                                                <div class="inputBox">
                                                    <input type="button" style="width: 32%" id="updatesupermarketinfo" name="updatesupermarketinfo" value="Update" class="btn">
                                                    <input type="button" style="width: 32%" id="addsupermarketinfo" name="addsupermarketinfo" value="Add" class="btn">
                                                    <input type="button" style="width: 32%" id="deletesupermarketinfo" name="deletesupermarketinfo" value="Delete" class="btn">
                                                </div>-->

                    </form>
                </div>
            </div>


            <div id="editCategory" class="tabcontent">
                <div class="row">

                    <form action="#" class="register-form" method="post" name="editcategoryform">

                        <h3>Edit Category</h3>

                        <span id="category-contents">
                        </span>

                        <!--                        <div style="margin-bottom: 20px; margin-top: 40px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 98%">Category ID: </label>                                
                                                    </div>
                                                    <div class="inputBox">
                                                        <input required style="width: 98%" id="categoryid" name="categoryid" type="text" placeholder="Enter the Category ID" class="box" maxlength="250">
                                                    </div>
                                                </div>
                        
                                                <div style="margin-bottom: 20px; margin-top: 20px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 98%">Category Name: </label>                                
                                                    </div>
                                                    <div class="inputBox">
                                                        <input required style="width: 98%" id="categoryname" name="categoryname" type="text" placeholder="Enter the Category Name" class="box" maxlength="250">
                                                    </div>
                                                </div>
                        
                                                <div style="margin-bottom: 20px; margin-top: 20px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 98%">Category Description: </label>                                
                                                    </div>
                                                    <div class="inputBox">
                                                        <input required style="width: 98%" id="categorydescription" name="categorydescription" type="text" placeholder="Enter the Category Description" class="box" maxlength="250">
                                                    </div>
                                                </div>
                        
                                                <div class="inputBox">
                                                    <input type="button" style="width: 32%" id="updateCategoryinfo" name="updateCategoryinfo" value="Update" class="btn">
                                                    <input type="button" style="width: 32%" id="addCategoryinfo" name="addCategoryinfo" value="Add" class="btn">
                                                    <input type="button" style="width: 32%" id="deleteCategoryinfo" name="deleteCategoryinfo" value="Delete" class="btn">
                                                </div>-->

                    </form>

                </div>
            </div>


            <div id="editbrand" class="tabcontent">
                <div class="row">

                    <form action="#" class="register-form" method="post" name="editbrandform">

                        <h3>Edit Brand</h3>

                        <span id="brand-contents">
                        </span>

                        <!--                        <div style="margin-bottom: 20px; margin-top: 40px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 98%">Brand ID: </label>                                
                                                    </div>
                                                    <div class="inputBox">
                                                        <input readonly="true" style="width: 98%" id="brandid" name="brandid" type="text" placeholder="Enter the Brand ID" class="box" maxlength="250">
                                                    </div>
                                                </div>
                        
                                                <div style="margin-bottom: 20px; margin-top: 20px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 98%">Brand Name: </label>                                
                                                    </div>
                                                    <div class="inputBox">
                                                        <input required style="width: 98%" id="brandname" name="brandname" type="text" placeholder="Enter the Brand Name" class="box" maxlength="250">
                                                    </div>
                                                </div>
                        
                        
                                                <div class="inputBox">
                                                    <input type="button" style="width: 32%" id="updateBrandinfo" name="updateCategoryinfo" value="Update" class="btn">
                                                    <input type="button" style="width: 32%" id="addBrandinfo" name="addCategoryinfo" value="Add" class="btn">
                                                    <input type="button" style="width: 32%" id="deleteBrandinfo" name="deleteCategoryinfo" value="Delete" class="btn">
                                                </div>-->

                    </form>

                </div>
            </div>


            <div id="viewproductstock" class="tabcontent">
                <div class="row">

                    <form action="#" class="register-form" method="post" name="viewallstocks">

                        <h3>View All Stock</h3>

                        <span id="stock-contents">
                        </span>

                        <!--                        <div style="margin-bottom: 15px; margin-top: 80px;">
                                                    <img src="image/category/drinks.png" alt="product image" width="200" height="200" style="margin-top: 20px; margin-bottom: 20px;">
                                                </div>
                        
                                                <div style="margin-bottom: 20px; margin-top: 20px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 98%">Product ID: </label>                                
                                                    </div>
                                                    <div class="inputBox">
                                                        <input readonly="true" style="width: 98%" id="WMproductid" name="WMproductid" type="text" placeholder="Enter the Product ID" class="box" maxlength="250">
                                                    </div>
                                                </div>
                        
                                                <div style="margin-bottom: 20px; margin-top: 20px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 98%">Product Name: </label>                                
                                                    </div>
                                                    <div class="inputBox">
                                                        <input readonly="true" style="width: 98%" id="WMproductname" name="WMproductname" type="text" placeholder="Enter the Product Name" class="box" maxlength="250">
                                                    </div>
                                                </div>
                        
                        
                                                <div style="margin-bottom: 20px; margin-top: 20px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 32%">Unit: </label>
                                                        <label style="width: 32%">Price: </label>
                                                        <label style="width: 32%">Quantity: </label>
                                                    </div>
                                                    <div class="inputBox">
                                                        <input readonly="true" style="width: 32%" id="WMproductunit" name="WMproductunit" type="text" placeholder="Enter Product Unit" class="box" maxlength="10">
                                                        <input readonly="true" style="width: 32%" id="WMproductprice" name="WMproductprice" type="text" placeholder="Enter Product Price" class="box" maxlength="10">
                                                        <input required style="width: 32%" id="WMproductquantity" name="WMproductquantity" type="text" placeholder="Enter Product Qauntity" class="box" maxlength="6">
                                                    </div>
                                                </div>
                        
                                                <div style="margin-bottom: 20px; margin-top: 20px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 32%">Supermarket: </label>
                                                        <label style="width: 32%">Category: </label>
                                                        <label style="width: 32%">Brand: </label>
                                                    </div>
                                                    <div class="inputBox">
                                                        <input readonly="true" style="width: 32%" id="WMproductsupermarket" name="WMproductsupermarket" type="text" placeholder="Enter Product Unit" class="box" maxlength="250">
                                                        <input readonly="true" style="width: 32%" id="WMproductcategory" name="WMproductcategory" type="text" placeholder="Enter Product Price" class="box" maxlength="250">
                                                        <input readonly="true" style="width: 32%" id="WMproductbrand" name="WMproductbrand" type="text" placeholder="Enter Product Qauntity" class="box" maxlength="250">
                                                    </div>
                                                </div>
                        
                        
                                                <div style="margin-bottom: 15px;">
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 49%">Last Restocked Date: </label>
                                                        <label style="width: 49%">Last Restocked By: </label>
                                                    </div>
                                                    <div class="inputBox">
                                                        <input readonly="true" style="width: 49%; font-size: 1.4rem; color: #666;" id="WMproduct_lastrestockeddate" name="WMproduct_lastrestockeddate" type="date" placeholder="Enter Date" class="box">
                                                        <input readonly="true" style="width: 49%" id="WMproduct_lastrestockedby" name="WMproduct_lastrestockedby" type="text" class="box" placeholder="Enter Name" class="box" maxlength="250">
                                                    </div>
                                                </div>
                        
                        
                                                <div class="inputBox">
                                                    <input type="button" style="width: 49%" id="addWMproductinfo" name="addWMproductinfo" value="Add" class="btn">
                                                    <input type="button" style="width: 49%" id="updateWMproductinfo" name="updateWMproductinfo" value="Update" class="btn">
                                                    <input type="button" style="width: 32%" id="deleteWMproductinfo" name="deleteWMproductinfo" value="Delete" class="btn">
                                                </div>-->

                    </form>

                </div>
            </div>

            <div id="viewpacking" class="tabcontent">
                <div class="row">

                    <form action="#" class="register-form" method="post" name="viewallorders">

                        <h3>View All Items to Pack</h3>

                        <span id="pack-contents">
                        </span>

                        <!--                        <div style="margin-bottom: 20px;">
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 32%">Filter By: </label>
                                                    </div>
                                                    <div class="inputBox">
                                                        <select required style="width: 74%; color: #666;" name="statusfilter" id="statusfilter" class="box">
                                                            <option value="All">All</option>
                                                            <option value="Packing in Process">Packing in Process</option>
                                                            <option value="Packing Completed">Packing Completed</option>
                                                        </select>
                                                        <input type="button" style="width: 22%;" id="searchbtn" name="searchbtn" value="Search" class="btn">
                                                    </div>
                                                </div>
                        
                        
                                                 table for everything
                                                <table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                                                    <tr style="text-align: center; background: #6D6875; color: white;">
                                                        <th>No</th>
                                                        <th>Customer Name</th>
                                                        <th>Customer Address</th>
                                                        <th>Order ID</th>
                                                        <th>Status</th>
                                                    </tr>
                        
                                                    <tr style="text-align: center;">
                                                        <td>1</td>
                                                        <td>Melriza</td>
                                                        <td>Jurong East</td>
                                                        <td>#123</td>
                                                        <td>Packing in Process</td>
                                                    </tr>
                        
                                                    <tr style="text-align: center;">
                                                        <td>2</td>
                                                        <td>Joey</td>
                                                        <td>Orchard</td>
                                                        <td>#456</td>
                                                        <td>Packing Completed</td>
                                                    </tr>
                        
                                                </table>
                        
                        
                                                 table for one record
                                                <div style="margin-bottom: 20px; margin-top: 50px;">
                        
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 98%">Order ID: </label>                                
                                                    </div>
                                                    <div class="inputBox">
                                                        <input readonly="true" style="width: 98%" id="orderid" name="orderid" type="text" placeholder="Enter the Order ID" class="box" maxlength="250">
                                                    </div>
                                                </div>
                        
                                                <div style="margin-bottom: 50px;">
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 32%">Customer Name: </label>
                                                        <label style="width: 32%">Customer Address: </label>
                                                        <label style="width: 32%">Status: </label>
                                                    </div>
                                                    <div class="inputBox">
                                                        <input required style="width: 32%; font-size: 1.4rem; color: #666;" id="packer_customername" name="packer_customername" type="text" placeholder="Enter Customer Name" class="box">
                                                        <input required style="width: 32%" id="packer_customeraddress" name="packer_customeraddress" type="text" class="box" placeholder="Enter Customer Address" class="box" maxlength="250">
                                                        <select required style="width: 32%; color: #666;" name="individualcustomerstatus" id="individualcustomerstatus" class="box">
                                                            <option value="">- Select Status -</option>
                                                            <option value="Packing in Process">Packing in Process</option>
                                                            <option value="Packing Completed">Packing Completed</option>
                                                        </select>
                                                    </div>
                                                </div>
                        
                        
                                                <h4>Items Ordered: </h4>
                                                <table class="carttable" style="font-size: 1.4rem; margin-top: 15px;">
                                                    <tr style="text-align: center; background: #B5838D; color: white;">
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Quantity</th>
                                                    </tr>
                        
                                                    <tr style="text-align: center; background: white;">
                                                        <td>1</td>
                                                        <td>Maggi Mee</td>
                                                        <td>2</td>
                                                    </tr>
                        
                                                    <tr style="text-align: center; background: white;">
                                                        <td>2</td>
                                                        <td>Carrots</td>
                                                        <td>3</td>
                                                    </tr>
                        
                                                </table>
                        
                                                <div class="inputBox">
                                                    <input type="button" style="width: 98%; margin-top: 50px;" id="updatecardstatus" name="updatecardstatus" value="Update Status" class="btn">
                                                </div>-->


                    </form>

                </div>
            </div>


            <div id="deliveryinformation" class="tabcontent">
                <div class="row">

                    <form action="#" class="register-form" method="post" name="deliveryinformation">

                        <h3>View All Items to Deliver</h3>

                        <span id="delivery-contents">
                        </span>

                        <!--                        <div style="margin-bottom: 20px;">
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 98%">Filter By: </label>
                                                    </div>
                                                    <div class="inputBox">
                                                        <select required style="width: 74%; color: #666;" name="deliveryfilterstatus" id="deliveryfilterstatus" class="box">
                                                            <option value="">- Select Status -</option>
                                                            <option value="Packing Completed">Packing Completed</option>
                                                            <option value="Delivering in Process">Delivering in Process</option>
                                                            <option value="Delivering in Process">Delivery Completed</option>
                                                        </select>
                                                        <input type="button" style="width: 22%;" id="searchbtn" name="searchbtn" value="Search" class="btn">
                                                    </div>
                                                </div>
                        
                        
                                                 table for everything
                                                <table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                                                    <tr style="text-align: center; background: #6D6875; color: white;">
                                                        <th>No</th>
                                                        <th>Customer Name</th>
                                                        <th>Customer Address</th>
                                                        <th>Order ID</th>
                                                        <th>Status</th>
                                                    </tr>
                        
                                                    <tr style="text-align: center;">
                                                        <td>1</td>
                                                        <td>Melriza</td>
                                                        <td>Jurong East</td>
                                                        <td>#123</td>
                                                        <td>Packing Completed</td>
                                                    </tr>
                        
                                                    <tr style="text-align: center;">
                                                        <td>2</td>
                                                        <td>Joey</td>
                                                        <td>Orchard</td>
                                                        <td>#456</td>
                                                        <td>Delivering in Process</td>
                                                    </tr>
                        
                                                    <tr style="text-align: center;">
                                                        <td>3</td>
                                                        <td>Benny</td>
                                                        <td>Parkway Parade</td>
                                                        <td>#789</td>
                                                        <td>Delivery Completed</td>
                                                    </tr>
                        
                                                </table>
                        
                                                <div style="margin-bottom: 50px; margin-top: 50px;">
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 32%">Status: </label>
                                                    </div>
                                                    <div class="inputBox">
                                                        <select required style="width: 98%; color: #666;" name="individualcustomerdeliverystatus" id="individualcustomerdeliverystatus" class="box">
                                                            <option value="">- Select Status -</option>
                                                            <option value="Packing Completed">Packing Completed</option>
                                                            <option value="Delivering in Process">Delivering in Process</option>
                                                            <option value="Delivering in Process">Delivery Completed</option>
                                                        </select>
                                                    </div>
                                                </div>
                        
                                                <div class="inputBox">
                                                    <input type="button" style="width: 98%;" id="updatedeliverystatus" name="updatedeliverystatus" value="Update Status" class="btn">
                                                </div>-->

                    </form>

                </div>
            </div>


            <div id="orderinformation" class="tabcontent">
                <div class="row">

                    <form action="#" class="register-form" method="post" name="viewallorderinformatio ">
                        <h3>View All Order Information</h3>

                        <span id="order_all-contents">
                        </span>
                    </form>

                </div>
            </div>

        </section>


        <?php include "footer.php"; ?>

    </body>
</html>
