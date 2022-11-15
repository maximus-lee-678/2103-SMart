<!DOCTYPE html>
<html lang="en">
    <title>Track My Purchases</title>
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
            <h1>Track My Purchases</h1>
        </div>

        <section class="products">
            

            <!--if got purchase history-->
            <div class="wrapper" style="margin-bottom: 30px;">  
                <!-- Accordion -->
                <button class="accordion" style="font-size: 1.4rem; margin-top: 20px;"><label style="float: left;">Order No: #1 (28/11/2022)</label><label style="margin-left: 20px;">Status: Delivery in Process</label></button>
                <div class="panel" style="font-size: 1.4rem;">
                  
                    <table class="carttable" style="font-size: 1.4rem; margin-top: 20px;">
                        <tr style="text-align: center; background: #6D6875; color: white;">
                            <th colspan="2">Product</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Quantity</th>
                        </tr>
                        <tr id="' . $row["prod_id"] . '">
                            <td><image src="image/home-bg.jpg" class="imagesize" /></td>
                            <td>d</td>
                            <td>d</td>
                            <td>d</td>
                            <td>d</td>
                        </tr>
                   </table>
                    
                    
                    <table class="carttable" style="font-size: 1.4rem; margin-top: 40px;">
           <tr style="text-align: center; background: white;">
               <td colspan="2">Delivery Fee: </td>
               <td colspan="2">$</td>
           </tr>
           <tr style="text-align: center; background: white;">
               <td colspan="2">Service Fee: </td>
               <td colspan="2">$</td>
           </tr>
           <tr style="text-align: center; background: white;">
               <td colspan="2">Final Cost: </td>
               <td colspan="2">$</td>
           </tr>
       </table>
                    
                </div>

                
                
                
                <button class="accordion" style="font-size: 1.4rem; margin-top: 20px;"><label style="float: left;">Order No: #1 (28/11/2022)</label><label style="margin-left: 20px;">Status: Delivery in Process</label></button>
                <div class="panel" style="font-size: 1.4rem;">
                  
                    <table class="carttable" style="font-size: 1.4rem; margin-top: 20px;">
                        <tr style="text-align: center; background: #6D6875; color: white;">
                            <th colspan="2">Product</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Quantity</th>
                        </tr>
                        <tr id="' . $row["prod_id"] . '">
                            <td><image src="image/home-bg.jpg" class="imagesize" /></td>
                            <td>d</td>
                            <td>d</td>
                            <td>d</td>
                            <td>d</td>
                        </tr>
                   </table>
                    
                    
                    <table class="carttable" style="font-size: 1.4rem; margin-top: 40px;">
           <tr style="text-align: center; background: white;">
               <td colspan="2">Delivery Fee: </td>
               <td colspan="2">$</td>
           </tr>
           <tr style="text-align: center; background: white;">
               <td colspan="2">Service Fee: </td>
               <td colspan="2">$</td>
           </tr>
           <tr style="text-align: center; background: white;">
               <td colspan="2">Final Cost: </td>
               <td colspan="2">$</td>
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


