<?php
    include( 'includes/config.inc.php' );

    $errors = array();

    if( isset( $_POST[ 'email' ] ) ){
        //form validation
        
        if( !filter_var( $_POST[ 'email' ], FILTER_VALIDATE_EMAIL ) ){
            $errors[ 'email' ]
                = '<p class="error">Please enter a valid email address.</p>';
        }
        
        // validate message
        if( strlen( $_POST[ 'message' ] ) < 2 ){
            $errors[ 'message' ]
                = '<p class="error">Please enter a message.</p>';
        }
        
        // re-captcha server-side intergration
        $curl = curl_init();
        
        $post_data = array(
            'secret'    => '6LeQx2YUAAAAAEHsGh-acgMMrVPc0W8Xb1ab4NZt',
            'response'  => $_POST[ 'g-recaptcha-response' ],
            'remoteip'  => $_SERVER[ 'REMOTE_ADDR' ]
        
        );
        
        $options = array(
            CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_POSTFIELDS => $post_data
        
        );
        
        // apply settings to curl connection
        curl_setopt_array( $curl, $options );
        
        $response = curl_exec( $curl );
        
        $response = json_decode( $response, true );
        
        curl_close( $curl );
        
        if( !$response[ 'success' ] ){
            $errors[ 'reCAPTCHA' ]
                = '<p class="error">The reCAPTCHA spambot detection failed. Please try again.</p>';
        }
        
        if( count( $errors ) == 0 ){
            
            $from_address = $_POST[ 'email' ];
            $message      = $_POST[ 'message' ];
            $to_address   =  'hello@denisemensah.com';
            
            require( 'includes/libraries/PHPMailer/src/PHPMailer.php' );
            
            $mail =  new \PHPMailer\PHPMailer\PHPMailer;
            
            $mail ->setFrom( $from_address );
            $mail ->addAddress( $to_address, 'Denise' );
            $mail ->Subject = 'Contact Form Email';
            $mail ->isHTML( true );
            $mail ->Body = nl2br( $message );
            $mail ->Altbody = strip_tags( $message );
            
            if( $mail->send() ){
                
                
                header( 'Location: ' 
                         . $_SERVER[ 'PHP_SELF' ] 
                         . '?success' );
            } else{
                $errors[ 'server' ]
                    ='<p>There was a problem sending the email. Please try again later</p>';
            }

            
        }
    }


?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Denise Mensah</title>
    <link rel="stylesheet" href="css/style.css" />
<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
       
    <!--[if lt IE 9]>
	       <script src="js/html5shiv.min.js"></script>
        <![endif]-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>    
        <script src="js/main.js"></script>    
        <script src="https://www.google.com/recaptcha/api.js"></script>        
</head>

<body id="contact">
    <header id="site-header">
    <h1><a href="index.html">Denise Mensah</a></h1>
        <nav id="site-nav">
            <ul>
                <li><a href="/index.html">home</a></li>
                <li><a href="/work.html">work</a></li>
                <li><a href="/about.html">about</a></li>
                <li><a href="/contact.php">contact</a></li>

            </ul>
        </nav>
    </header>
    
        <h2 id="intro">Contact</h2>
        <p id="info">To get in touch with me concerning employment opportunities or general enquires you can leave a message here and I will get back to you ASAP. </p>
    <article>
        
        <?php if( !isset( $_GET[ 'success' ] ) ):  ?>
        <?php echo $errors[ 'server' ]; ?>
        <form method="post" action="<?php echo $_SERVER[ 'PHP_SELF' ]; ?>">
            <ol>
                <li>
                    <label>Email Address</label><?php echo $errors[ 'email' ] ?>
                    <input type="text" name="email" placeholder="example@example.com" value="<?php echo $_POST[ 'email' ]; ?>">
                </li>
                <li>
                    <label>Message</label><?php echo $errors[ 'message' ] ?>
                    <textarea name="message" placeholder="your message"><?php echo $_POST[ 'message' ];?></textarea>
                </li>
                    <li id="recaptcha">
                        <?php echo $errors[ 'reCAPTCHA' ]; ?>
<div class="g-recaptcha" data-sitekey="6LeQx2YUAAAAADvJUjPTRqvOh4dk7C3c7LJsUQ_6"></div>
                    </li>
                
                <li>
                    <input type="submit" name="send" value="send">
                </li>    
                </ol>
        </form>
    </article>
        
        <?php else: ?>
        <p class="success">Your message was succesfully sent!</p>
            <p class="success">
                <a href="<?php echo $_SERVER[ 'PHP_SELF' ]; ?>">
                    Send another email message.
                </a>
            </p>
        
        <?php endif; ?>

<footer id="site_footer">
    <div id="footer_content">
    <p id="copy">&copy; Denise Mensah 2019. All rights reserved.</p>
    

            <ul id="socials">
                <li><a href="https://github.com/dmensah17" target="_blank">github</a></li>
                <li><a href="https://www.linkedin.com/in/denisemensah/" target="_blank">linked-in</a></li>
                <li><a href="contact.php" target="_blank">email</a></li>

            </ul>
    </div>    
    </footer>
</body>

</html>