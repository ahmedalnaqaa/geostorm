<?php
namespace Todo\Bundle\UserBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use FOS\OAuthServerBundle\Controller\TokenController;
use FOS\UserBundle\Doctrine\UserManager;
use OAuth2\OAuth2;
use FOS\RestBundle\Controller\Annotations as Rest;
use Todo\Bundle\UserBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use JMS\DiExtraBundle\Annotation as DI;

class LoginController extends TokenController
{
    protected $userManager;

    /**
     * @DI\InjectParams({
     *      "server" = @DI\Inject("fos_oauth_server.server"),
     *      "userManager" = @DI\Inject("fos_user.user_manager"),
     * })
     *
     * @param OAuth2 $server
     */
    public function __construct(OAuth2 $server, UserManager $userManager)
    {
        parent::__construct($server);
        $this->userManager = $userManager;
    }

    public function tokenAction(Request $request)
    {
        $result = parent::tokenAction($request);

        return $result;
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   section="Oauth2",
     * )
     *
     * @Rest\Post("api/v2/oauth/token")
     * @Rest\QueryParam(name="access_token", description="Access Token")
     * @Rest\QueryParam(name="client_id", description="Client ID",)
     * @Rest\QueryParam(name="client_secret", description="Client Secret")
     * @Rest\QueryParam(
     *     name="grant_type",
     *     description="{ password, client_credentials, refresh_token }"
     * )
     * @Rest\QueryParam(name="username", description="Username")
     * @Rest\QueryParam(name="password", description="Password")
     * @Rest\QueryParam(name="refresh_token", description="Refresh Token")
     * @Rest\View()
     *
     * @param Request $request
     *
     * @return Response
     */

    public function loginAction(Request $request)
    {
        $username = $request->get('username');
        $user = $this->userManager->findUserByUsername($username);
        if ($user) {
            $request->request->set('username', $user->getUsername());
        }
        $request->request->set('grant_type','password');
        $result = parent::tokenAction($request);

        return $result;
    }
}