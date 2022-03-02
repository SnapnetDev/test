<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>HCMatrix Trial Onboarding</title>
    <link rel="stylesheet" type="text/css" href="{{asset('register_assets/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('register_assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('register_assets/css/filepond.min.css')}}">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet"> 
</head>
<body>
	<div class="col-md-6 right bluebg" width="100%" height="100%"></div>
	<div class="container">
		<div class="header">
			<a href="index.html">
				<img src="{{asset('register_assets/img/HCMatrix.png')}}" class="logo">
			</a>
			<a href="google.com" class="s2">
				<img src="{{asset('register_assets/img/mdi_earth.svg')}}">English
			</a>
		</div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 middle">
                    <div>
                        <h4 class="bluecolor">Let's get started</h4><br>
                        <h6>Follow our recommended workflow to get started. Upload your company’s grades, departments and users.</h6><br>
                        <div class="row">
                            <div class="col-1 middle">
                                <img src="{{asset('register_assets/img/mdi_checkbox-marked-circle-outline.svg')}}">
                            </div>
                            <div class="col-1 middle">
                                <img src="{{asset('register_assets/img/mdi_shield-star.svg')}}" class="icon">
                            </div>
                            <div class="col">
                                <ul class="middle">
                                    <li>
                                        <h6>Grades/Cadres</h6>
                                        <p class="b2 grey">Upload grades/cadres file</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-1 middle">
                                <img src="{{asset('register_assets/img/mdi_checkbox-marked-circle-outline.svg')}}">
                            </div>
                            <div class="col-1 middle">
                                <img src="{{asset('register_assets/img/mdi_account-group.svg')}}" class="icon">
                            </div>
                            <div class="col">
                                <ul class="middle">
                                    <li>
                                        <h6>Departments</h6>
                                        <p class="b2 grey">Upload company department file</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-1 middle">
                                <img src="{{asset('register_assets/img/mdi_checkbox-marked-circle-outline.svg')}}">
                            </div>
                            <div class="col-1 middle">
                                <img src="{{asset('register_assets/img/mdi_account.svg')}}" class="icon">
                            </div>
                            <div class="col">
                                <ul class="middle">
                                    <li>
                                        <h6>Users</h6>
                                        <p class="b2 grey">Upload list of users</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 midi justify-content-center ">
                    <form>
                        <div class="form-group">
                            <input class="" id="file_upload" type="file" >
                        </div>
                        
                        <div class="form-group">
                            <button class="btn btn-primary form-control bt" type="button" id="myBtn">Upload</button>
                        </div>
                    </form>
                </div>    
            </div>
        </div>
    </div>
		<div class="footer container">
			<hr>
			<div class="row">
				<div class="col-md-6">
					<p class="bluecolor s2">© 2020 Snapnet Nigeria Limited. All Rights Reserved. Terms & Privacy</p>
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{asset('register_assets/js/bootstrap.js')}}"></script>
    <script src="{{asset('register_assets/js/filepond-plugin-file-validate-type.js')}}"></script>
    <script type="text/javascript" src="{{asset('register_assets/js/filepond.min.js')}}"></script>
    <script>
        const inputElement = document.querySelector('input[id="file_upload"]');
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        const pond = FilePond.create( inputElement,{
            acceptedFileTypes: ['application/xlsx'],
            fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
                
                // Do custom type detection here and return with promise

                resolve(type);
            })
        } );
        FilePond.setOptions({
            server: {url:'api/',
                    headers:{
                        'X-CSRF-TOKEN':'{{csrf_token()}}'
                    }
                }
        });
    </script>
</body>
</html>