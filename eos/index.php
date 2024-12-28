<?php 
    session_start();
    
?>

<!DOCTYPE html> <html lang="en">
     <head> <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Responsive Landing Page</title> <!-- Bootstrap CSS --> 
    <link rel="stylesheet" type="text/css" href="../catalog/view/javascript/assets/css/bootstrap.min.css">
<!-- <link rel="stylesheet" type="text/css" href="assets/css/menu.css"> -->
<!-- <link rel='stylesheet' type="text/css" href="assets/css/swiper-bundle.min.css"> -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" /> -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css">
<link rel="stylesheet" href="assets/css/w3animation.css">
<style>
    .requestcall {
    background: #fc7e00;
    border: 0;
    border-radius: 10px 10px 0px 0px;
    position: fixed;
    top: 36%;
    right: -8px;
    z-index: 4;
    color: #fff;
    padding: 6px;
    font-weight: 500;
    text-transform: uppercase;
    transform: translatex(50px) rotate(-90deg);
    font-size: 15px;
    width: 170px;
    height: 54px;
}
p {
    font-size: 14px;
    line-height: 1.7;
    color: #828282;
}
.requestcall span {
    background: #41464b;
    padding: 5px 7px;
    font-size: 15px;
    border-radius: 100px;
    width: 30px;
    height: 30px;
}
.send-btn {
    background: #fc7e00;
    border: 0;
    border-radius: 10px;
    z-index: 2;
    color: #fff;
    text-align: center;
    width: 100%;
    padding: 10px;
}
.top-banner-bg {
    background: url(../catalog/view/javascript/assets/image/newpagebanner.jpg);
    width: 100%;
    height: 59vh;
    background-size: 100% 100%;
    background-repeat: no-repeat;
    position: relative;
}
img.end-girl-img {
    position: absolute;
    top: auto;
    bottom: 0;
    left: 12em;
    width: 30%;
}
img.end-saleicon {
    position: absolute;
    right: 7em;
    left: auto;
    top: 3em;
    width: 45%;
}
    .card{
        border: 1px solid #ededed;
        background-color: #fafafa;
    }
    .card h5 {
    font-size: 16px;
}
    .card-footer{
     border: none;
    background: none;
    margin: 0px;
    }
    .card-body{
        flex:none; padding: 7px;
    }
 .buy-now, .cart-now {
    text-decoration: none;
    text-align: center;
    padding: 6px 4px;
    transition: all-ease-in-out 1s;
    border: 1px solid #fc7e00;
    margin:0px 5px;
    }  
 .buy-now {
    background: #fc7e00;
    font-size: 14px;
    color: #fff; 
}
.cart-now {
    background: #fff;
    font-size: 14px;
    color: #fc7e00;
}
.menu-bar-web {
    position: absolute;
    top: 0px;
    width: 100%;
    z-index: 1;
    background: #fff;
    border-bottom: 1px solid #ffffff54;
}
.float-right-side {
    float: right;
    display: flex;
    align-items: center;
    margin: 0px 0px;
    padding: 0px;
}
.tophead-link-2 li {
    float: left;
    padding: 5px 0;
    margin: 5px 25px 5px 0;
    display: flex;
    align-items: center;
    position: relative;
}
.tophead-link-2 li i {
    color: #fc7e00;
    margin-right: 6px;
    font-size: 20px;
}
.tophead-link-2 li:last-child {
    margin: 5px 0;
}
.tophead-link-2 li {
    float: left;
    padding: 5px 0;
    margin: 5px 25px 5px 0;
    display: flex;
    align-items: center;
    position: relative;
}
.badge_pp {
    font-size: 13px;
    width: 20px;
    height: 20px;
    top: -6px;
    right: -12px;
    background: #198754;
    position: absolute;
    border-radius: 10px;
    text-align: center;
}

.footer {
  background-color: #f8f9fa;
  padding: 20px 0;
  text-align: center;
}

.footer a {
  display: block;
  margin-bottom: 10px;
}

.footer img {
  max-width: 100%;
  height: auto;
}

@media (min-width: 576px) {
  .footer {
    padding: 30px 0;
  }
}

@media (min-width: 768px) {
  .footer {
    padding: 40px 0;
  }
}

@media (min-width: 992px) {
  .footer {
    padding: 50px 0;
  }
}

@media (min-width: 1200px) {
  .footer {
    padding: 60px 0;
  }
}
@media only screen and (max-width:1024px) {
  .top-banner-bg {
    height: 42vh;
}
}
@media only screen and (max-width:768px){
  img.end-girl-img {
    left: 0em;
    width: 40%;
}
img.end-saleicon {
    right: 1em;
    top: 5em;
    width: 58%;
}
}
@media only screen and (max-width:425px){
  img.end-saleicon {
    right: 0;
    left: 2em;
    top: 4em;
    width: 68%;
    margin: 0 auto;
}
img.end-girl-img {
    left: 0;
    width: 55%;
    right: 0;
    margin: 0 auto;
}
.card h5 {
    font-size: 12px;
}
.buy-now, .cart-now {
    padding: 3px 2px;
    margin: 0px 0px;
}
}
@media only screen and (max-width:320px){
img.end-saleicon {
    width: 95%;
    left: 0px;
}
}
</style>

</head> 
<body>
  <nav class="navbar navbar-expand-lg navbar-light menu-bar-web">
    <div class="container">
    <a class="navbar-brand" href="#"><img src="../catalog/view/javascript/assets/image/urbanwoodlogo.png" alt="urbanwoodfurniture_logo" class="img-fluid  w-75" ></a>
    <ul class="tophead-link-2 float-right-side">

      <!-- <li id="wishli">
      <button class="btn border-none p-0 m-0" id="wishlist" onclick="openWishlist(this);" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight2" aria-controls="offcanvasRight2" fdprocessedid="givre3"> <i class="bx bx-heart"></i></button>
      <span class="badge_pp text-white">0</span>
      <span class="visually-hidden">unread messages</span>
      </li> -->
      <li id="cartli">
      <a href="../?route=checkout/cart" class="btn border-none p-0 m-0" id="cartlist" ><i class="bx bx-cart-alt"></i></a> 
      <?php 
        if(isset($_COOKIE['cartt'])) {
            if($_COOKIE['cartt'] != null){
            echo '<span class="badge_pp text-white"> '.$_COOKIE['cartt'].'</span>';
            }else{
                echo '<span class="badge_pp text-white"> 0</span>';
            }
        }else{
            echo '<span class="badge_pp text-white"> 0</span>';
        }
        ?>
      <span class="visually-hidden">unread messages</span> </li>
      </ul>
    </div>
    </nav>
     <header class="bg-primary text-white top-banner-bg"> 
        <img src="../catalog/view/javascript/assets/image/endalgirl.png" class="end-girl-img" alt="">
        <img src="../catalog/view/javascript/assets/image/endofsaleicon.png" class="end-saleicon" alt="">
     </header> <main class="container py-5"> 
        <div class="row"> 
            <!-- <?php for ($i = 1; $i <= 6; $i++): ?> 
            <div class="col-md-4 col-6"> <div class="card h-100"> 
                <img src="assets/image/newpage-p.jpg" class="card-img-top" alt="..."> 
                <div class="card-body"> 
                    <h5 class="card-title">Tirupati Home Temple <?php echo $i; ?></h5> 
                    <p class="card-text">This is a short description of product <?php echo $i; ?></p> 
                </div> 
                <div class="card-footer d-block d-md-flex align-item-center justify-cotent-between"> 
                    <button class="cart-now  w-md-50 w-100">Add to Cart</button> 
                    <button class="buy-now w-md-50 w-100">Buy Now</button> 
                </div> 
            </div> 
        </div> 
        <?php endfor; ?>  -->

        <div class="col-md-4 col-6 my-2"> <div class="card h-100 "> 
                <img src="https://www.urbanwood.in//image/cache/catalog/temple/tirupati/honey/honey-look-2-1920x1340.jpg" class="card-img-top" alt="..."> 
                <div class="card-body"> 
                    <h5 class="card-title">Tirupati Home Temple </h5> 
                </div> 
                <div class="card-footer d-block d-md-flex align-item-center justify-cotent-between"> 
                    <span class="w-md-50 w-100">17500 </span>
                    <button class="cart-now w-md-50 w-100">Add to Cart</button> 
                    <button class="review_ratings_login" value='175'>Quick View</button> 

                    <!-- <button class="buy-now w-md-50 w-100">Buy Now</button>  -->
                </div> 
            </div> 
        </div>
        <div class="col-md-4 col-6 my-2"> <div class="card h-100 "> 
                <img src="https://www.urbanwood.in//image/cache/catalog/console-tables/milan/honey/milan-honey-1-1920x1340.jpg" class="card-img-top" alt="..."> 
                <div class="card-body"> 
                    <h5 class="card-title">Tirupati Home Temple </h5> 
                </div> 
                <div class="card-footer d-block d-md-flex align-item-center justify-cotent-between"> 
                    <button class="cart-now  w-md-50 w-100" onclick="addtocart(1,175)">Add to Cart</button> 
                    <button class="buy-now w-md-50 w-100">Buy Now</button> 
                </div> 
            </div> 
        </div>
        <div class="col-md-4 col-6 my-2"> <div class="card h-100 "> 
                <img src="https://www.urbanwood.in//image/cache/catalog/wall-shelves/poppy/walnut/walnut-look-1-1920x1340.jpg" class="card-img-top" alt="..."> 
                <div class="card-body"> 
                    <h5 class="card-title">Tirupati Home Temple </h5> 
                </div> 
                <div class="card-footer d-block d-md-flex align-item-center justify-cotent-between"> 
                    <button class="cart-now  w-md-50 w-100">Add to Cart</button> 
                    <button class="buy-now w-md-50 w-100">Buy Now</button> 
                </div> 
            </div> 
        </div>
        <div class="col-md-4 col-6 my-2"> <div class="card h-100 "> 
                <img src="https://www.urbanwood.in//image/cache/catalog/Bookshelve/naro/walnut/walnut-look-1920x1340.jpg" class="card-img-top" alt="..."> 
                <div class="card-body"> 
                    <h5 class="card-title">Tirupati Home Temple </h5> 
                </div> 
                <div class="card-footer d-block d-md-flex align-item-center justify-cotent-between"> 
                    <button class="cart-now  w-md-50 w-100">Add to Cart</button> 
                    <button class="buy-now w-md-50 w-100">Buy Now</button> 
                </div> 
            </div> 
        </div>
        <div class="col-md-4 col-6 my-2"> <div class="card h-100 "> 
                <img src="https://www.urbanwood.in//image/cache/catalog/Bookshelve/rop/walnut/walnut-look-1920x1340.jpg" class="card-img-top" alt="..."> 
                <div class="card-body"> 
                    <h5 class="card-title">Tirupati Home Temple </h5> 
                </div> 
                <div class="card-footer d-block d-md-flex align-item-center justify-cotent-between"> 
                    <button class="cart-now  w-md-50 w-100">Add to Cart</button> 
                    <button class="buy-now w-md-50 w-100">Buy Now</button> 
                </div> 
            </div> 
        </div>
        <div class="col-md-4 col-6 my-2"> <div class="card h-100 "> 
                <img src="https://www.urbanwood.in//image/cache/catalog/shoe-racks/driftwood/walnut/Walnut-look-1920x1340.jpg" class="card-img-top" alt="..."> 
                <div class="card-body"> 
                    <h5 class="card-title">Tirupati Home Temple </h5> 
                </div> 
                <div class="card-footer d-block d-md-flex align-item-center justify-cotent-between"> 
                    <button class="cart-now  w-md-50 w-100">Add to Cart</button> 
                    <button class="buy-now w-md-50 w-100">Buy Now</button> 
                </div> 
            </div> 
        </div>
        <div class="col-md-4 col-6 my-2"> <div class="card h-100 "> 
                <img src="https://www.urbanwood.in//image/cache/catalog/shoe-racks/trace/walnut/walnut-1920x1340.jpg" class="card-img-top" alt="..."> 
                <div class="card-body"> 
                    <h5 class="card-title">Tirupati Home Temple </h5> 
                </div> 
                <div class="card-footer d-block d-md-flex align-item-center justify-cotent-between"> 
                    <button class="cart-now  w-md-50 w-100">Add to Cart</button> 
                    <button class="buy-now w-md-50 w-100">Buy Now</button> 
                </div> 
            </div> 
        </div>
       
        <div class="col-md-4 col-6 my-2"> <div class="card h-100 "> 
                <img src="https://www.urbanwood.in//image/cache/catalog/tv-units/bia/teak/look-1920x1340.jpg" class="card-img-top" alt="..."> 
                <div class="card-body"> 
                    <h5 class="card-title">Tirupati Home Temple </h5> 
                </div> 
                <div class="card-footer d-block d-md-flex align-item-center justify-cotent-between"> 
                    <button class="cart-now  w-md-50 w-100">Add to Cart</button> 
                    <button class="buy-now w-md-50 w-100">Buy Now</button> 
                </div> 
            </div> 
        </div>

        <div class="col-md-4 col-6 my-2"> <div class="card h-100 "> 
                <img src="https://www.urbanwood.in//image/cache/catalog/coffee-tables/bliss-sets/honey/honey-look-01-1920x1340.jpg" class="card-img-top" alt="..."> 
                <div class="card-body"> 
                    <h5 class="card-title">Tirupati Home Temple </h5> 
                </div> 
                <div class="card-footer d-block d-md-flex align-item-center justify-cotent-between"> 
                    <button class="cart-now  w-md-50 w-100">Add to Cart</button> 
                    <button class="buy-now w-md-50 w-100">Buy Now</button> 
                </div> 
            </div> 
        </div>

        <div class="col-md-4 col-6 my-2"> <div class="card h-100 "> 
                <img src="https://www.urbanwood.in//image/cache/catalog/bedside-table/laverock/walnut/walnut-look-1920x1340.jpg" class="card-img-top" alt="..."> 
                <div class="card-body"> 
                    <h5 class="card-title">Tirupati Home Temple </h5> 
                </div> 
                <div class="card-footer d-block d-md-flex align-item-center justify-cotent-between"> 
                    <button class="cart-now  w-md-50 w-100">Add to Cart</button> 
                    <button class="buy-now w-md-50 w-100">Buy Now</button> 
                </div> 
            </div> 
        </div>

        <div class="col-md-4 col-6 my-2"> <div class="card h-100 "> 
                <img src="https://www.urbanwood.in//image/cache/catalog/bedside-table/morgana/walnut/walnut-look-1920x1340.jpg" class="card-img-top" alt="..."> 
                <div class="card-body"> 
                    <h5 class="card-title">Tirupati Home Temple </h5> 
                </div> 
                <div class="card-footer d-block d-md-flex align-item-center justify-cotent-between"> 
                    <button class="cart-now  w-md-50 w-100">Add to Cart</button> 
                    <button class="buy-now w-md-50 w-100">Buy Now</button> 
                </div> 
            </div> 
        </div>
        <div class="col-md-4 col-6 my-2"> <div class="card h-100 "> 
                <img src="https://www.urbanwood.in//image/cache/catalog/nested-table/scallion/teak/teak-look-1-1920x1340.jpg" class="card-img-top" alt="..."> 
                <div class="card-body"> 
                    <h5 class="card-title">Tirupati Home Temple </h5> 
                </div> 
                <div class="card-footer d-block d-md-flex align-item-center justify-cotent-between"> 
                    <button class="cart-now  w-md-50 w-100">Add to Cart</button> 
                    <button class="buy-now w-md-50 w-100">Buy Now</button> 
                </div> 
            </div> 
        </div>


        
        

        
    </div> 
</main> 
<div class="sidecall">
        <button type="button" class=" requestcall" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
            Request Call
            <span>
                <i class='bx bx-phone-call bx-tada'></i>
            </span>
        </button>
    </div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="modal-header text-center d-block">
                <h5 class="modal-title" id="exampleModalLabel">Request a Callback</h5>
                <p class="text-center">We will contact you as soon as we have received your information.
                </p>
            </div>
            <div class="modal-body">
                <form id="myform">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="user-name" required>
                    </div>
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Email Adderess</label>
                        <input type="text" name="email" class="form-control" id="email-address" required>
                    </div>
                    <div class="mb-3">
                        <label for="recipient-num" class="col-form-label">Contact No.</label>
                        <input type="text" name="mobile" class="form-control" id="con-num" required>
                    </div>
                    <div class="mb-3">
                      <label for="state" class="col-form-label">State</label>
                        <select name="state" id="state" name="state" class="form-control" required>
			<option value="">Select state</option>
<option value="Andhra Pradesh">Andhra Pradesh</option>
<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
<option value="Arunachal Pradesh">Arunachal Pradesh</option>
<option value="Assam">Assam</option>
<option value="Bihar">Bihar</option>
<option value="Chandigarh">Chandigarh</option>
<option value="Chhattisgarh">Chhattisgarh</option>
<option value="Dadar and Nagar Haveli">Dadar and Nagar Haveli</option>
<option value="Daman and Diu">Daman and Diu</option>
<option value="Delhi">Delhi</option>
<option value="Lakshadweep">Lakshadweep</option>
<option value="Puducherry">Puducherry</option>
<option value="Goa">Goa</option>
<option value="Gujarat">Gujarat</option>
<option value="Haryana">Haryana</option>
<option value="Himachal Pradesh">Himachal Pradesh</option>
<option value="Jammu and Kashmir">Jammu and Kashmir</option>
<option value="Jharkhand">Jharkhand</option>
<option value="Karnataka">Karnataka</option>
<option value="Kerala">Kerala</option>
<option value="Madhya Pradesh">Madhya Pradesh</option>
<option value="Maharashtra">Maharashtra</option>
<option value="Manipur">Manipur</option>
<option value="Meghalaya">Meghalaya</option>
<option value="Mizoram">Mizoram</option>
<option value="Nagaland">Nagaland</option>
<option value="Odisha">Odisha</option>
<option value="Punjab">Punjab</option>
<option value="Rajasthan">Rajasthan</option>
<option value="Sikkim">Sikkim</option>
<option value="Tamil Nadu">Tamil Nadu</option>
<option value="Telangana">Telangana</option>
<option value="Tripura">Tripura</option>
<option value="Uttar Pradesh">Uttar Pradesh</option>
<option value="Uttarakhand">Uttarakhand</option>
<option value="West Bengal">West Bengal</option>
</select>
			</div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Message:</label>
                        <textarea class="form-control" name="message" id="message-text" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer text-center">
                <button type="button" id="subbtn" onclick="submitCallRequest('myform','subbtn')" class="send-btn">Send message</button>
            </div>
        </div>
    </div>
<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <a href="https://www.urbanwood.in"><img src="../catalog/view/javascript/assets/image/urbanwoodlogo.png" alt="Your Website Logo"></a>
        <p>&copy; 2024 urbanwood.in. All rights reserved.</p>
      </div>
    </div>
  </div>
</footer>
<!-- <div id="login_for_review" class="modal hide"  role="dialog">

</div> -->
<div class="modal show" id="login_for_review" tabindex="1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap JS --> 

<script src="../catalog/view/javascript/assets/js/bootstrap.bundle.min.js"></script>
<script src="../catalog/view/javascript/assets/js/jquery1.1.1.min.js"></script>
<script src="../catalog/view/javascript/common.js"></script>
<script>
  function addtocart(quantity, product_id) {
var carttotal;
	$.ajax({
		url: 'http://localhost/opencartpro/?route=checkout/cart/add',
		type: 'post',
		data: {"quantity":quantity,"product_id":product_id},
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
			$('.alert-dismissible, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));

						if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}

				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}

			if (json['success']) {
				//$('.breadcrumb').after('<div class="alert alert-success alert-dismissible">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        $('body').prepend('<div class="success-popup ">	<span class="warrning-svg">	<svg id="smile"		width="35" height="35" viewBox="0 0 41 41" fill="none">					  <path d="M16.2432 29.9911L16.5968 30.3439L16.95 29.9907L30.5886 16.352L30.9422 15.9985L30.5886 15.6449L27.7128 12.7691L27.3593 12.4156L27.0057 12.7691L16.592 23.1828L12.2699 18.8682L11.9158 18.5148L11.5626 18.869L8.69086 21.7489L8.33792 22.1028L8.69174 22.4558L16.2432 29.9911ZM0.823975 20.662C0.823975 9.72375 9.72375 0.823975 20.662 0.823975C31.6002 0.823975 40.5 9.72375 40.5 20.662C40.5 31.6002 31.6002 40.5 20.662 40.5C9.72375 40.5 0.823975 31.6002 0.823975 20.662Z" stroke="#33C300"></path>  </svg>					</span>	<span class="warrning-text">   <p>' + json['success'] + '</p>	</span>				  </div>');
				$('#cartli .badge_pp').text(json['total']);
                createCookie('cartt', json['total'],1)
				$('html, body').animate({ scrollTop: 0 }, 'slow');

				$('#cart > ul').load('index.php?route=common/cart/info ul li');
			}
		},
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
	});
}
function createCookie(name, value, days) {
    var expires;
    if (days) {
        var now = new Date();
        var time = now.getTime();
        time += 3600 * 1000;
        now.setTime(time);
        expires = "; expires=" + now.toGMTString();
    }
    else {
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}
</script>
<script>
     function submitCallRequest(idd,btn){
    if(!formRule(idd)) return false;
    $('#'+btn).text('Processing...');

   
    $.ajax({
      url:'../?route=information/requestcallback',
      data:$('#'+idd).serialize(),
      type:'POST',
    // dataType:'JSON',
    success:function(res){      
      console.log(res);
        var thanksHtml = '<div class="ThankuMsgx" style="text-align:center;">\n\
        <img loading="lazy" src="https://i.pinimg.com/originals/06/ae/07/06ae072fb343a704ee80c2c55d2da80a.gif" style="width: 136px;"><br>\n\
        <h1 style="color: gray;">Success!</h1><br>\n\
        <p>Our team will contact you shortly.</p><br>';
        $('#'+idd).html('');
        $('#'+idd).html(thanksHtml);
        $('#'+btn).remove();
      
    }
  });
  }

  $(document).on('click', '.review_ratings_login', function () {
        var data = $('.review_ratings_login').val();
         var url = 'http://localhost/opencartpro/?route=api/product&product_id='+data;
        $.ajax({
            type: 'GET',
            url: url,
            success: function (output) {
            //$('.modal-body').html(output).modal('show');//now its working
            var arrayLength = output['products'].length;
            for (var i = 0; i < arrayLength; i++) {
    console.log(output['products'][i]);
    //Do something
}
            // alert(JSON.parse(output))
            },
            error: function(output){
            alert("fail");
            }
        });
    });
    </script>
</body> 
</html>