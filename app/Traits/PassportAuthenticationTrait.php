<?php


use App\Exceptions\AuthenticationFailedException;
use Laravel\Passport\Exceptions\OAuthServerException;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JwtParser;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ServerRequestInterface;

trait PassportAuthenticationTrait
{
    private $password_client;

    public function __construct(
        AuthorizationServer $server,
        TokenRepository $tokens,
        JwtParser $jwt
    ) {
        parent::__construct($server, $tokens, $jwt);
        $this->password_client = Passport::client()
            ->firstWhere([
                ['password_client', true],
                ['revoked', false],
            ]);
    }

    public function login($username, $password)
    {
        $payload = [
            'username'      => $username,
            'password'      => $password,
            'client_id'     => $this->password_client->id,
            'client_secret' => $this->password_client->secret,
            'grant_type'    => 'password',
            'scope'         => '',
        ];

        try {
            $request = app(ServerRequestInterface::class);

            return parent::issueToken($request->withParsedBody($payload));
        } catch (OAuthServerException $exception) {
            throw AuthenticationFailedException::invalidCredential();
        }
    }
}

