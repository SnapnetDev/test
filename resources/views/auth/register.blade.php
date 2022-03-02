<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>HCMatrix Trial Onboarding</title>
	<link rel="stylesheet" type="text/css" href="{{asset('register_assets/css/style.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('register_assets/css/bootstrap.css')}}">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet"> 
</head>
<body>
	<div class="col-md-6 right bluebg" width="100%" height="100%"></div>
	<div class="container">
		<div class="header">
			<a href="index.html">
				<img style="width: 70px;" src="{{asset('register_assets/img/hcm.png')}}" class="logo">
			</a>
			{{-- <a href="google.com" class="s2">
				<img src="{{asset('register_assets/img/mdi_earth.svg')}}">English
			</a> --}}
		</div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 middle">
                	<div>
                		<img class="row col img-fluid" src="{{asset('register_assets/img/folks.png')}}">
                    	<h4>Get started with HCMatrix for free.</h4>
                    	<h5>Automate your HR processes seamlessly and get increased productivity with a 360° view of your human resourses.</h5>
                    </div>
                </div>
                <div class="col-md-6 midi justify-content-center">
                	<div>
                		<h6 class="bluecolor">Start your 14-day trial</h6>
                    	<form id="register_form" >
                        	<div class="form-group">
                            @csrf
                        		<label class="b2">Name</label>
                        		<input class="form-control b2" type="text" name="name" required placeholder="Your Full Name">
                        	</div>
                        	<div class="form-group">
                        		<label class="b2">Company Name</label>
                        		<input class="form-control b2" type="text" name="company_name" required placeholder="Your Company Name">
                        	</div>
                        	<div class="form-group">
                        		<label class="b2">Email</label>
                        		<input class="form-control b2" type="email" name="email" required placeholder="Your Email Address">
                        	</div>
                        	<div class="form-group">
                        		<label class="b2">Password</label>
                        	<input class="form-control b2" type="password" name="password" required placeholder="Your Password">
                        </div>
                        	<div class="form-group">
                        		<button class="btn btn-primary form-control bt" type="submit" >Let's get started</button>
                        	</div>
                        	</form>
                    	<div class="row col center"><p class="s2">Already have a HCMatrix Account?</p>&nbsp;<a class="s2" href="{{url('login')}}">SIGN IN</a></div>
                	</div>
                	</div>    
            	</div>
        </div>
    </div>
    <div id="myModal" class="popupmodal">
    	<div class="popup-modal-content">
    		<span id="modal-close" class="popup-close bt">close</span>
    		<div class="container">
    			<div class="row">
    				<div class="col-md-6">
    					<img class="img-fluid" src="{{asset('register_assets/img/Allura.png')}}">
    				</div>
    				<div class="col-md-6 middle">
    					<div>
                        <h6>You have succeded in creating an environment.</h6>
    						<h6>Would you like to load sample data into your HCMatrix Trial?</h6>
    						<p class="b2">Load sample data to your HCMatrix account to help you settle in comfortably as you familiarize yourself with various features. <br><br>Note: You can delete sample data at any point in time</p>
    						<a href="#" id="link" class="bt btn btn-primary">No, I will fill my own data</a><br><br>
    						<button class="btn btn-secondary bt" id='autoload' type="button">Yes, Generate Data</button>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
		<div class="footer container">
			<hr>
			<div class="row">
				<div class="col-md-6">
					<p class="bluecolor s2">© {{date('Y')}} Snapnet Nigeria Limited. All Rights Reserved. Terms & Privacy</p>
				</div>
				<div class="col-md-6 right">
					<a href="facebook.com" class="icon"><img src="{{asset('register_assets/img/mdi_facebook.svg')}}"></a>
					<a href="twitter.com" class="icon"><img src="{{asset('register_assets/img/mdi_twitter.svg')}}"></a>
					<a href="youtube.com" class="icon"><img src="{{asset('register_assets/img/mdi_youtube.svg')}}"></a>
					<a href="linkedin.com" class="icon"><img src="{{asset('register_assets/img/mdi_linkedin.svg')}}"></a>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
        register_form = document.querySelector("#register_form");
        var modal = document.querySelector("#myModal");
		var btn = document.querySelector("#myBtn");
		var span = document.querySelector("#modal-close");
		var link = document.querySelector("#link");
        var autoload_btn = document.querySelector("#autoload");
        
        register_form.onsubmit = function(event){
        event.preventDefault();
        var form = this;
        
        formdata = new FormData(form);
        
            
            
        fetch('{{route('register_company')}}', {
        method: "POST",
        body: formdata
        })
        
        .then(json => {
            console.log(json);
            modal.style.display = "block";
        })
        .catch(err => console.log(err));
        };

        autoload_btn.onclick = function(event){
            event.preventDefault();
            fetch("{{url('registration-auto')}}")
            // Handle success
            
            .then(json => {
                console.log(json);
            })    //print data to console
            .catch(err => console.log('Request Failed', err)); // Catch errors
			modal.style.display = "none";
            location.assign("{{url('home')}}");
        };
		
		// autoload_btn.onclick = function(){
		// 
		// };
		span.onclick = function(){
			modal.style.display = "none";
		};
		link.onclick = function(){
            
		}
		window.onclick = function(event){
			if(event.target == modal){
				modal.style.display = "none";
			}
		}
	</script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script type="text/javascript" src="{{asset('register_assets/js/bootstrap.js')}}"></script>
</body>
</html>