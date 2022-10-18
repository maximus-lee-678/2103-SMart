
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>

        <!-- font awesome cdn link  -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

        <!-- custom css file link  -->
        <link rel="stylesheet" href="css/style.css">



        <style>

            .container{overflow: hidden}
            .tab{float: left;}
            .tab-2{margin-left: 50px}
            .tab-2 input{display: block;margin-bottom: 10px}
            tr{transition:all .25s ease-in-out}
            tr:hover{background-color:#EEE;cursor: pointer}

        </style>


    </head>
    <body>

        <!-- header section starts  -->

        <header class="header">

            <a href="home.php" class="logo"> <i class="fas fa-shopping-basket"></i> groco </a>

            <nav class="navbar">
                <a href="index.php">home</a>
                <a href="shop.php">shop</a>
                <a href="about.php">about</a>
                <a href="review.php">review</a>
                <a href="blog.php">blog</a>
                <a href="contact.php">contact</a>
            </nav>

            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <div id="search-btn" class="fas fa-search"></div>
                <div id="cart-btn" class="fas fa-shopping-cart"></div>
                <div id="login-btn" class="fas fa-user"></div>
            </div>

            <form action="" class="search-form">
                <input type="search" placeholder="search here..." id="search-box">
                <label for="search-box" class="fas fa-search"></label>
            </form>

            <div class="shopping-cart">
                <div class="box">
                    <i class="fas fa-times"></i>
                    <img src="image/cart-1.jpg" alt="">
                    <div class="content">
                        <h3>organic food</h3>
                        <span class="quantity">1</span>
                        <span class="multiply">x</span>
                        <span class="price">$18.99</span>
                    </div>
                </div>
                <div class="box">
                    <i class="fas fa-times"></i>
                    <img src="image/cart-2.jpg" alt="">
                    <div class="content">
                        <h3>organic food</h3>
                        <span class="quantity">1</span>
                        <span class="multiply">x</span>
                        <span class="price">$18.99</span>
                    </div>
                </div>
                <div class="box">
                    <i class="fas fa-times"></i>
                    <img src="image/cart-3.jpg" alt="">
                    <div class="content">
                        <h3>organic food</h3>
                        <span class="quantity">1</span>
                        <span class="multiply">x</span>
                        <span class="price">$18.99</span>
                    </div>
                </div>
                <h3 class="total"> total : <span>56.97</span> </h3>
                <a href="#" class="btn">checkout cart</a>
            </div>

            <form action="" class="login-form">
                <div class="inputBox">
                    <a href="login.php" class="btn">Login Now</a>
                </div>
                <div class="inputBox">
                    <a href="register.php" class="btn">Register An Account</a>
                </div>
            </form>

        </header>

        <!-- header section ends -->




        <br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br><br><br><br>


<section class="contact" style="margin-top: 100px; margin-bottom: 20px; background-color: aquamarine;">

    <div class="row">

        <div class="container">
            <div class="tab tab-1">
                <table id="table" border="1">
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Age</th>
                    </tr>
                    <tr>
                        <td>A1</td>
                        <td>B1</td>
                        <td>10</td>
                    </tr>
                    <tr>
                        <td>A3</td>
                        <td>B3</td>
                        <td>30</td>
                    </tr>
                    <tr>
                        <td>A2</td>
                        <td>B2</td>
                        <td>20</td>
                    </tr>
                </table>
            </div>
            <div class="tab tab-2">
                First Name :<input type="text" name="fname" id="fname">
                Last Name :<input type="text" name="lname" id="lname">
                Age :<input type="number" name="age" id="age">

                <button onclick="addHtmlTableRow();">Add</button>
                <button onclick="editHtmlTbleSelectedRow();">Edit</button>
                <button onclick="removeSelectedRow();">Remove</button>
            </div>
        </div>
   
    </div>

</section>



        
        <br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br><br><br><br>

        

        <script>

            var rIndex,
                    table = document.getElementById("table");

            // check the empty input
            function checkEmptyInput()
            {
                var isEmpty = false,
                        fname = document.getElementById("fname").value,
                        lname = document.getElementById("lname").value,
                        age = document.getElementById("age").value;

                if (fname === "") {
                    alert("First Name Connot Be Empty");
                    isEmpty = true;
                } else if (lname === "") {
                    alert("Last Name Connot Be Empty");
                    isEmpty = true;
                } else if (age === "") {
                    alert("Age Connot Be Empty");
                    isEmpty = true;
                }
                return isEmpty;
            }

            // add Row
            function addHtmlTableRow()
            {
                // get the table by id
                // create a new row and cells
                // get value from input text
                // set the values into row cell's
                if (!checkEmptyInput()) {
                    var newRow = table.insertRow(table.length),
                            cell1 = newRow.insertCell(0),
                            cell2 = newRow.insertCell(1),
                            cell3 = newRow.insertCell(2),
                            fname = document.getElementById("fname").value,
                            lname = document.getElementById("lname").value,
                            age = document.getElementById("age").value;

                    cell1.innerHTML = fname;
                    cell2.innerHTML = lname;
                    cell3.innerHTML = age;
                    // call the function to set the event to the new row
                    selectedRowToInput();
                }
            }

            // display selected row data into input text
            function selectedRowToInput()
            {

                for (var i = 1; i < table.rows.length; i++)
                {
                    table.rows[i].onclick = function ()
                    {
                        // get the seected row index
                        rIndex = this.rowIndex;
                        document.getElementById("fname").value = this.cells[0].innerHTML;
                        document.getElementById("lname").value = this.cells[1].innerHTML;
                        document.getElementById("age").value = this.cells[2].innerHTML;
                    };
                }
            }
            selectedRowToInput();

            function editHtmlTbleSelectedRow()
            {
                var fname = document.getElementById("fname").value,
                        lname = document.getElementById("lname").value,
                        age = document.getElementById("age").value;
                if (!checkEmptyInput()) {
                    table.rows[rIndex].cells[0].innerHTML = fname;
                    table.rows[rIndex].cells[1].innerHTML = lname;
                    table.rows[rIndex].cells[2].innerHTML = age;
                }
            }

            function removeSelectedRow()
            {
                table.deleteRow(rIndex);
                // clear input text
                document.getElementById("fname").value = "";
                document.getElementById("lname").value = "";
                document.getElementById("age").value = "";
            }
        </script>






        <!-- footer section starts  -->
        <?php include "footer.php"; ?>
        <!-- footer section ends -->













        <!-- custom css file link  -->
        <script src="js/script.js"></script>
        <script src="js/password.js"></script>

    </body>
</html>