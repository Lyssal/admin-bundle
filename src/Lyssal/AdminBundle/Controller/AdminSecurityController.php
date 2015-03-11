<?php
namespace Lyssal\AdminBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Contrôleur pour se connecter à la console administrative.
 * Basé sur SonataUser.
 */
class AdminSecurityController extends ContainerAware
{
    /**
     * Connexion.
     */
    public function loginAction(Request $request)
    {
        if ($this->container->get('security.context')->getToken()->getUser() instanceof UserInterface)
            return new RedirectResponse($this->container->get('router')->generate('sonata_admin_dashboard'));

        $error = null;
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR)->getMessage();
        elseif (null !== $request->getSession() && $request->getSession()->has(SecurityContext::AUTHENTICATION_ERROR))
        {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR)->getMessage();
            $request->getSession()->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        if ($this->container->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            $refererUri = $request->server->get('HTTP_REFERER');
            return new RedirectResponse($refererUri && $refererUri != $request->getUri() ? $refererUri : $this->container->get('router')->generate('sonata_admin_dashboard'));
        }

        return $this->container->get('templating')->renderResponse('LyssalAdminBundle:Admin:Security/login.html.'.$this->container->getParameter('fos_user.template.engine'), array(
                'last_username' => (null === $request->getSession()) ? '' : $request->getSession()->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
                'csrf_token'    => ($this->container->has('form.csrf_provider') ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate') : null),
                'base_template' => $this->container->get('sonata.admin.pool')->getTemplate('layout'),
                'admin_pool'    => $this->container->get('sonata.admin.pool')
            ));
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data)
    {
        $template = sprintf('FOSUserBundle:Security:login.html.%s', $this->container->getParameter('fos_user.template.engine'));

        return $this->container->get('templating')->renderResponse($template, $data);
    }

    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
