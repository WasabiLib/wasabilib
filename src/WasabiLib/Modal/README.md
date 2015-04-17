/**
 * Class WasabiModalBaseConfigurator
 * @package WasabiLib\Modal
 *
 * @example
 * EXAMPLE 1:
 * $preRenderConfig = new Modal\WasabiModalBaseConfigurator(array(
        Modal\WasabiModalBaseConfigurator::TITLE => "Mein Modal-Fenster"
            , Modal\WasabiModalBaseConfigurator::CONTENT => "Mein toller Body"
            , Modal\WasabiModalBaseConfigurator::CLASSES => array("my_class", "fade")
            , Modal\WasabiModalBaseConfigurator::CLOSE_BUTTON => array("buttonClasses" => array("my-class"), "symbolClasses" => array("my-symbol-class"))
        //            , "closeButton" => false
        , Modal\WasabiModalBaseConfigurator::INLINE_CONFIG => array(
            Modal\WasabiModalBaseConfigurator::DATA_BACKDROP => Modal\WasabiModalBaseConfigurator::STATIC_CONST
            , Modal\WasabiModalBaseConfigurator::DATA_KEYBOARD => "true"
            )
        ,Modal\WasabiModalBaseConfigurator::BUTTONS => array(
            array(Modal\WasabiModalBaseConfigurator::ID => "my_button"
                , Modal\WasabiModalBaseConfigurator::CLASSES => array(Modal\WasabiModalBaseConfigurator::CLASS_BUTTON_DEFAULT, "ajax_element")
                , Modal\WasabiModalBaseConfigurator::DATA_DISMISS => Modal\WasabiModalBaseConfigurator::MODAL
                , Modal\WasabiModalBaseConfigurator::BUTTON_TEXT => "Schliessen")
                , array(Modal\WasabiModalBaseConfigurator::TEMPLATE => "button"
                , Modal\WasabiModalBaseConfigurator::BUTTON_TEXT => "Drücken")
                , array(Modal\WasabiModalBaseConfigurator::TEMPLATE => "button"
                , Modal\WasabiModalBaseConfigurator::CLASSES => array(Modal\WasabiModalBaseConfigurator::CLASS_BUTTON_PRIMARY)
                , Modal\WasabiModalBaseConfigurator::BUTTON_TEXT => "Save changes")
            )
        ));

    EXAMPLE 2:
        $preRenderConfig = new Modal\WasabiModalBaseConfigurator(array(
            Modal\WasabiModalBaseConfigurator::CLASSES => array("my_class"),
            Modal\WasabiModalBaseConfigurator::BUTTONS => array(
            array(Modal\WasabiModalBaseConfigurator::TEMPLATE => "button"
            , Modal\WasabiModalBaseConfigurator::CLASSES => array(Modal\WasabiModalBaseConfigurator::CLASS_BUTTON_PRIMARY)
            , Modal\WasabiModalBaseConfigurator::DATA_DISMISS => Modal\WasabiModalBaseConfigurator::MODAL
            , Modal\WasabiModalBaseConfigurator::BUTTON_TEXT => "OK")
            )
        ));

    EXAMPLE 3:
        $viewModel = new ViewModel();
        $viewModel->setTemplate("testContent");
        $viewModel->setTemplate("heidelpay-registration/heidelpay-registration/modal.phtml");

        $preRenderConfig = new Modal\WasabiModalBaseConfigurator(array(
            Modal\WasabiModalBaseConfigurator::CLASSES => array("my_class", "fade"),
            "size" => "centerModal",
            Modal\WasabiModalBaseConfigurator::CONTENT => $viewModel
            , Modal\WasabiModalBaseConfigurator::BUTTONS => array(
            array(Modal\WasabiModalBaseConfigurator::TEMPLATE => "button"
            , Modal\WasabiModalBaseConfigurator::CLASSES => array(Modal\WasabiModalBaseConfigurator::CLASS_BUTTON_PRIMARY)
            , Modal\WasabiModalBaseConfigurator::DATA_DISMISS => Modal\WasabiModalBaseConfigurator::MODAL
            , Modal\WasabiModalBaseConfigurator::BUTTON_TEXT => "OK")
            )
        ));
 */