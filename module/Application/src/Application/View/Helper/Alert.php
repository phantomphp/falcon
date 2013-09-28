<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class Alert extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CustomHelper
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Render the alerts using Foundation styles
     * 
     * @return string
     * */    
    public function render()
    {
        $htmlFormat = <<<HTML
<div data-alert class="alert-box radius %s">
  %s
  <a href="#" class="close">&times;</a>
</div>
HTML;
        $html = '';
        $flashMessenger = $this->getServiceLocator()->get('flashmessenger');
        if ($flashMessenger->hasErrorMessages()) {
            foreach ($flashMessenger->getErrorMessages() as $message) {
                $html .= sprintf($htmlFormat, 'alert', htmlspecialchars($message));
            }
        }
        if ($flashMessenger->hasSuccessMessages()) {
            foreach ($flashMessenger->getSuccessMessages() as $message) {
                $html .= sprintf($htmlFormat, 'success', htmlspecialchars($message));
            }
        }
        if ($flashMessenger->hasInfoMessages()) {
            foreach ($flashMessenger->getInfoMessages() as $message) {
                $html .= sprintf($htmlFormat, '', htmlspecialchars($message));
            }
        }
        
        if ($html) {
            $html = <<<HTML
<div id="alerts" class="hide">$html</div>
<script>
  $(".alert-aware").prepend($("#alerts").html());
</script>
HTML;
        }
        
        return $html;
    }

    public function __invoke()
    {
        return $this;
    }
}
