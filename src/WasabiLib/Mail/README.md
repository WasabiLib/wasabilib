Mail Module
===========
The Mail Module is pre-configured as a service that can be retrieved by calling the service locator.

            $body = new ViewModel();
            $body->setTemplate("responsive.phtml");

            $mail = $this->getServiceLocator()->get("Mail");
            $mail->setSubject("My Subject");
            $mail->setTo("recipient@address.com");
            $mail->setBody("My Message" || $body);
            $mail->send();

Environment and Settings
========================
One must set the server and the environment within the config.php of the module.

        "env" => array(
            "type" => "local"
            //"type" => "develop"
            //"type" => "production"
        ),

        "WasabiMail" => array(
            "transporter" => array(
            /**
            * local configuration to save mails as text
            */
            "local" => array(
            "base" => __DIR__,
            "target" => "/localMails/"),

            /**
            * you have a staging or development system with access to a mail server
            */
            "develop" => array(
                "port" => 25,
                "to" => "development@yourdomain.de",
                "name" => "mail.yourmailserver.local",
                "host" =>"mail.yourmailserver.local"),
            ),
        ),
        
One can define three types of environment. Local, develop and production.

        local: Usage on a local system. The Messages will be saved within the folder localMails in the module folder.
        develop: One can configure a local or global Email server. It is recommended to use a pre-defined to:address for testing.
        production: In production mode Sendmail is used. This can be changed in the Module.php only.