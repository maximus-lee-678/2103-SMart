<!DOCTYPE html>
<html lang="en">
    <title>My Food List</title>
    <?php
    session_start();
    include "head.php";
    ?>    
    <body>
        
        <style>
            /*ax accordiance*/

.accordion {
  background-color: #6d6875;
  color: whitesmoke;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
}

.active, .accordion:hover {
  background-color: #b5838d;
}

.accordion:after {
  content: '\002B';
  color: whitesmoke;
  font-weight: bold;
  float: right;
  margin-left: 5px;
  
}

.active:after {
  content: "\2212";
}

.panel {
  padding: 0 18px;
  background-color: white;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
}
        </style>
       
        
        <!-- header section starts  -->
        <?php include "nav.php"; ?>
        <!-- header section ends -->
        <div class="heading">
            <h1>My Food List</h1>
        </div>

        <section class="products">
            

            <!--if got purchase history-->
            <div class="wrapper" style="margin-bottom: 30px;">  
                
                
                <p style="font-size: 1.4rem; background-color: greenyellow; border: 1px solid grey; padding: 10px;">You have 4 food item that is Expiring in 3 Days!</p>
                
                
                <!-- Accordion -->
                <button class="accordion" style="font-size: 1.4rem; margin-top: 20px;"><label style="float: left;">Order No: #1 (28/11/2022)</label></button>
                <div class="panel" style="font-size: 1.4rem;">
                  
                    <table class="carttable" style="font-size: 1.4rem; margin-top: 20px;">
                        <tr style="text-align: center; background: #6D6875; color: white;">
                            <th colspan="2">Product</th>
                            <th>Total</th>
                            <th>Quantity</th>
                            <th>Expiry Date</th>
                            <th colspan="2"></th>
                        </tr>
                        <tr id="' . $row["prod_id"] . '">
                            <td><image src="image/home-bg.jpg" class="imagesize" /></td>
                            <td>d</td>
                            <td>d</td>
                            <td>d</td>
                            <td>d</td>
                            <td style="color: red;">Expiring in 2 Weeks</td>
                            <td><button class="btn">FINISHED</button></td>
                        </tr>
                        <tr id="' . $row["prod_id"] . '">
                            <td><image src="image/home-bg.jpg" class="imagesize" /></td>
                            <td>d</td>
                            <td>d</td>
                            <td>d</td>
                            <td>d</td>
                            <td style="color: green;">Expiring in 1 Month</td>
                            <td><button class="btn">FINISHED</button></td>
                        </tr>
                   </table>
                    
                    
                </div>

                
                
                <button class="accordion" style="font-size: 1.4rem; margin-top: 20px;"><label style="float: left;">Order No: #2 (28/11/2022)</label></button>
                <div class="panel" style="font-size: 1.4rem;">
                  
                    <table class="carttable" style="font-size: 1.4rem; margin-top: 20px;">
                        <tr style="text-align: center; background: #6D6875; color: white;">
                            <th colspan="2">Product</th>
                            <th>Total</th>
                            <th>Quantity</th>
                            <th>Expiry Date</th>
                            <th colspan="2"></th>
                        </tr>
                        <tr id="' . $row["prod_id"] . '">
                            <td><image src="image/home-bg.jpg" class="imagesize" /></td>
                            <td>d</td>
                            <td>d</td>
                            <td>d</td>
                            <td>d</td>
                            <td style="color: red;">Expiring in 2 Weeks</td>
                            <td><button class="btn">FINISHED</button></td>
                        </tr>
                        <tr id="' . $row["prod_id"] . '">
                            <td><image src="image/home-bg.jpg" class="imagesize" /></td>
                            <td>d</td>
                            <td>d</td>
                            <td>d</td>
                            <td>d</td>
                            <td style="color: green;">Expiring in 1 Month</td>
                            <td><button class="btn">FINISHED</button></td>
                        </tr>
                   </table>
                    
                    
                </div>
                
                
                
                

            </div>


        </section>
        <?php include "footer.php"; ?>

        <script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
</script>
    </body>
</html>


