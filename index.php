<?php
    require_once "vendor/autoload.php";
    use GuzzleHttp\Client;
    use \Mailjet\Resources;

    $msg_error = '<div class="alert alert-danger" role="alert">';
    $msg_success = '<div class="alert alert-success" role="alert">';

    if(isset($_POST['submit'])){
        $error = '';
        $success ='';
        if(!empty($_POST['contact_name']) && !empty($_POST['contact_firstname']) && !empty($_POST['contact_msg']) && !empty($_POST['contact_email'])) {
            // Recaptcha Google API
            $secretKey = "6LfG0aYbAAAAAAOuHANnV8YdGJDtvycIB2KKm24R";
            $responseKey = $_POST["g-recaptcha-response"];
            $userIP = $_SERVER['REMOTE_ADDR'];
            // Form input values 
            $name = htmlspecialchars($_POST['contact_name']);
            $firstname = htmlspecialchars($_POST['contact_firstname']);
            $email = htmlspecialchars($_POST['contact_email']);
            $message = htmlspecialchars($_POST['contact_msg']);
            
            // Send it to see if it is ok 
            $url ="https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
            $response = file_get_contents($url);
            $response = json_decode($response);
            if (filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL)) {

                // if $response is a success
                if (isset($response->success) && $response->success === true) {
                    $body = [
                        'Messages' => [
                            [
                            'From' => [
                                'Email' => "julie.barn9@icloud.com",
                                'Name' => "Moi"
                            ],
                            'To' => [
                                [
                                    'Email' => "julie.barn9@icloud.com",
                                    'Name' => "Moi"
                                ]
                            ],
                            'Subject' => "Greetings from Mailjet.",
                            'HTMLPart' => "<h3>Dear User, welcome to Mailjet!</h3><br />May the delivery force be with you!"
                            ]
                        ]
                    ];
                    
                    $client = new Client([
                        // Base URI is used with relative requests
                        'base_uri' => 'https://api.mailjet.com/v3.1/',
                    ]);
                    
                    $response = $client->request('POST', 'send', [
                        'json' => $body,
                        'auth' => ['1a15bc80f1e2d698c705d303bcf594ec', '0bf387f9095578ea0bf4a970f4e5e4fd']
                    ]);
                    
                    if ($response->getStatusCode() == 200) {
                        $body = $response->getBody();
                        $response = json_decode($body);
                        if ($response->Messages[0]->Status == 'success') {
                            // Success message if email has been send succefully
                            $success = $msg_success."Email envoyé avec succès !</div>";
                        } else {
                            $error .= $msg_error."Erreur, email non envoyé ...</div>";
                        }
                    }
                    //endIf
                } else {
                    // Error message if recaptcha is not valid
                    $captchaError .= $msg_error.'recaptcha est invalide !</div>';
                }
                // endIf
            }else{
                // Error message if fields are empty
                $error .= $msg_error."Email invalide...</div>";
            }
        }else{
            // Error message if fields are empty
            $error .= $msg_error."Attention, des champs requis sont manquants...</div>";
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez Terre Nourricière</title>

    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet" href="css/bootstrap-5.0.2/css/bootstrap.css">
    <!-- Google font h1 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marck+Script&display=swap" rel="stylesheet">

    <!-- Google recaptcha script-->
    <script src="https://www.google.com/recaptcha/api.js"></script>
</head>

<body>
    <div id="page">
        <!-- Entête de la zone considérée -->

        <header id="header" class="container-fluid h-100">
            <!-- TOP HEADER -->
            <div class="row border-bottom border-white py-1 sub-nav bg-grey">
                <div class="col-12">
                    <div class="scroll-nav text-center">
                        <a class="px-3 my-auto text-uppercase scroll-nav-item"
                            href="https://www.terrenourriciere.org/">Accueil</a>
                        <a class="px-3 my-auto text-uppercase scroll-nav-item"
                            href="https://www.terrenourriciere.org/a-propos">À propos</a>
                        <a class="px-3 my-auto text-uppercase scroll-nav-item"
                            href="https://www.terrenourriciere.org/Actualites">Actualités</a>
                        <a class="px-3 my-auto text-uppercase scroll-nav-item"
                            href="https://www.terrenourriciere.org/adhesion.php">Adhésion</a>
                        <a class="px-3 my-auto text-uppercase scroll-nav-item"
                            href="https://www.terrenourriciere.org/newsletter">Newsletter</a>
                    </div>
                </div>
            </div>

            <!-- LOGO -->
            <div class="container-fluid h-100 header-container">
                <div class="row py-4 h-header">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <img src="images/logo_terre_nourriciere.webp" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
            <h1 class="col-12 d-flex align-items-center justify-content-center mt-5 mb-3 title-contact">Vous souhaitez
                nous contacter ?</h1>
        </header>

        <!-- Nav. principale de la page -->
        <nav></nav>

        <!-- Les à-cotés de la page -->
        <aside></aside>

        <!-- Contenu textuel de la page -->
        <section>
            <!-- Contact form container -->
            <form class="container contact-container" action="index.php" method="POST">
                <div class="mb-3">
                    <label for="contact_name">Nom</label>
                    <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Nom">
                </div>
                <div class="mb-3">
                    <label for="contact_firstname">Prénom</label>
                    <input type="text" class="form-control" id="contact_firstname" name="contact_firstname"
                        placeholder="Prénom">
                </div>
                <div class="mb-3">
                    <label for="contact_email">Email</label>
                    <input type="type" class="form-control" id="contact_email" name="contact_email"
                        placeholder="toto@exemple.com">
                </div>
                <div class="mb-3">
                    <label for="contact_msg">Message</label>
                    <textarea type="text" class="form-control" id="contact_msg" name="contact_msg" rows="10"
                        cols="100"></textarea>
                </div>
                <div id="msg_error">
                    <?php 
                        echo $error;
                        echo $captchaError;
                        echo $emailError;
                    ?>
                </div>
                <div id="msg_error">
                    <?php 
                        echo $success;
                    ?>
                </div>
                <div id="recaptcha">
                    <div class="g-recaptcha" data-sitekey="6LfG0aYbAAAAAL2lXnvLPkUGbIm-YRhegdMMNhL0" data-callback="verifyCaptcha"></div>
                    <div id="g-recaptcha-error"></div>
                </div>
                <button type="submit" name='submit' data-bs-toggle='modal' data-bs-target='#exampleModal' class="btn btn-warning text-white align-center" name="contact_validate" onclick="">Envoyer mon message</button>
            </form>
        </section>

        <!-- Pied-de-page de la page -->
        <footer class="container-fluid mt-5">
            <!-- LOGO -->
            <div class="container">
                <div class="row py-4">
                    <div class="col-12 d-flex flex-wrap justify-content-center align-items-center">
                        <img src="images/logo-white.webp" alt="" class="img-fluid">
                    </div>
                </div>
            </div>

            <!-- SUB NAVIGATION -->
            <div class="row border-bottom border-white py-1 sub-nav">
                <div class="col-12">
                    <div class="d-flex flex-wrap justify-content-center align-items-center">
                        <a class="px-3 text-capitalize d-block text-white"
                            href="https://www.terrenourriciere.org/">Accueil</a>
                        <a class="px-3 text-capitalize d-block text-white"
                            href="https://www.terrenourriciere.org/a-propos">À propos</a>
                        <a class="px-3 text-capitalize d-block text-white"
                            href="https://www.terrenourriciere.org/Actualites">Actualités</a>
                        <a class="px-3 text-capitalize d-block text-white"
                            href="https://www.terrenourriciere.org/adhesion.php">Adhésion</a>
                        <a class="px-3 text-capitalize d-block text-white"
                            href="https://www.terrenourriciere.org/newsletter">Newsletter</a>
                    </div>
                </div>
            </div>

            <div class="row py-3">
                <div class="col-md-12">
                    <p class="text-white text-center">Immeuble Le Thèbes - 26 allée de Mycènes - 34000 Montpellier</p>
                    <p class="text-white text-center">Tél : 09 53 44 34 34 - Email :
                        <a class="text-white"
                            href="mailto:contact@terrenourriciere.org">contact@terrenourriciere.org</a>
                    </p>
                    <p class="text-white text-center">© Terre Nourrcière 2020 / XHTML / CSS</p>
                </div>
            </div>

            <div class="row py-3" id="social">
                <div class="col-md-12">
                    <div class="d-flex justify-content-center flex-wrap">
                        <a rel="noreferrer" target="_blank" href="https://www.vimeo.com/user4783921" aria-label="Viméo">
                            <img src="images/bouton_vimeo_footer.webp" alt="Vimeo">

                        </a>
                        <a rel="noreferrer" target="_blank" href="https://www.instagram.com/terre.nourriciere/"
                            aria-label="Instagram">
                            <img src="images/bouton_instagram_footer.webp" alt="Instagram">
                        </a>
                        <a rel="noreferrer" target="_blank"
                            href="https://www.facebook.com/TerreNourriciereCommunication/" aria-label="Facebook">
                            <img src="images/bouton_facebook_footer.webp" alt="Facebook">
                        </a>
                        <a rel="noreferrer" target="_blank" href="https://www.linkedin.com/company/terre-nourricière/"
                            aria-label="Linkedin">
                            <img src="images/bouton_linkedin_footer.webp" alt="Linkedin">
                        </a>
                    </div>
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-md-12 d-flex justify-content-center flex-column">
                    <p class="text-white text-center">La Région Occitanie soutient les activités de Terre Nourricière</p>
                    <img style="max-height: 60px; width:auto;" class="img-fluid d-block mx-auto mt-2" src="squelettes/img/refonte/logo_occitanie.jpg" alt="Région Occitanie">
                </div>
            </div> -->
        </footer>
    </div>

    <!-- Script Bootstrap-->
    <script src="js/bootstrap-js/bootstrap.js"></script>
    <!-- Script jQuery -->
    <script type="text/javascript" src="js/jquery_3_3_1.js"></script>
</body>

</html>